# Monitoring Kubernetes dan Laravel

Direktori ini disiapkan untuk chart Helm `prometheus-community/kube-prometheus-stack`.
Konfigurasi dapat disimpan sekarang dan diterapkan setelah `kubectl`, `helm`, serta
kubeconfig cluster tersedia.

## Komponen

- `kube-prometheus-stack-values.yaml`: konfigurasi Prometheus, Grafana,
  kube-state-metrics, node-exporter, dan kubelet/cAdvisor.
- `backend-servicemonitor.yaml`: scrape endpoint Laravel `/metrics` setiap 15 detik.
- `backend-prometheusrule.yaml`: alert backend down, database down, dan pod restart.
- `../05-app.yaml`: Service Laravel memiliki label `app: laravel` dan named port
  `http` agar dapat ditemukan oleh ServiceMonitor.

Release Helm harus memakai nama `kube-prometheus-stack`. Nama ini sengaja dibuat
konsisten dengan label `release` pada ServiceMonitor dan PrometheusRule.

## Instalasi saat cluster tersedia

```bash
kubectl config current-context
kubectl apply -f k8s/01-namespaces.yaml

helm repo add prometheus-community https://prometheus-community.github.io/helm-charts
helm repo update
helm upgrade --install kube-prometheus-stack prometheus-community/kube-prometheus-stack \
  --namespace monitoring \
  --create-namespace \
  --values k8s/monitoring/kube-prometheus-stack-values.yaml \
  --wait \
  --timeout 10m

kubectl apply -f k8s/05-app.yaml
kubectl apply -f k8s/monitoring/backend-servicemonitor.yaml
kubectl apply -f k8s/monitoring/backend-prometheusrule.yaml
```

Pastikan image Laravel yang dipasang ke cluster sudah dibangun dari source terbaru,
karena endpoint `/metrics` ditambahkan di aplikasi.

## Pemeriksaan resource

```bash
kubectl get pods -n monitoring
kubectl get servicemonitor,prometheusrule -n monitoring
kubectl get svc laravel-service -n myapp --show-labels
kubectl get endpoints laravel-service -n myapp
kubectl get --raw /apis/monitoring.coreos.com/v1
```

## Membuka Prometheus UI

Cara yang paling portabel untuk cluster lokal maupun remote:

```bash
kubectl port-forward -n monitoring service/kube-prometheus-stack-prometheus 9090:9090
```

Buka `http://localhost:9090`, kemudian periksa menu **Status > Targets**. Target
Laravel harus berstatus `UP` dan menampilkan path `/metrics`.

Query verifikasi backend:

```promql
laravel_app_up
laravel_database_up
laravel_database_probe_duration_seconds
up{namespace="myapp",service="laravel-service"}
```

Query verifikasi resource pod/container:

```promql
kube_pod_info{namespace="myapp"}
sum(rate(container_cpu_usage_seconds_total{namespace="myapp",container!="",image!=""}[5m])) by (pod)
sum(container_memory_working_set_bytes{namespace="myapp",container!="",image!=""}) by (pod)
kube_pod_container_status_restarts_total{namespace="myapp"}
```

Jika Prometheus UI diakses langsung melalui node, konfigurasi values mengekspos
NodePort `30090`. Grafana tersedia pada NodePort `30030`. Port-forward tetap lebih
aman untuk cluster remote karena tidak mengharuskan port node dibuka ke jaringan.

## Troubleshooting scrape backend

```bash
kubectl describe servicemonitor laravel-backend -n monitoring
kubectl port-forward -n myapp service/laravel-service 8000:80
curl http://localhost:8000/metrics
```

Respons endpoint harus bertipe `text/plain` dan berisi metrik seperti
`laravel_app_up 1`. Bila ServiceMonitor tidak muncul sebagai target, periksa bahwa
release Helm bernama `kube-prometheus-stack` dan Service Laravel memiliki label
`app=laravel` serta port bernama `http`.
