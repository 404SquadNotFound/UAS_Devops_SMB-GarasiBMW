<?php

namespace Tests\Feature;

use App\Http\Controllers\ItemCategoryController;
use App\Models\ItemCategory;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use Tests\TestCase;
use Mockery;

class ItemCategoryTest extends TestCase
{
    use RefreshDatabase;

    private function makeCategory(string $name, ?string $desc = null): ItemCategory
    {
        return ItemCategory::create([
            'name'         => $name,
            'descriptions' => $desc,
            'employee_id'  => null,
            'created_by'   => null,
        ]);
    }

    // TC-KTR-04 - Statement Coverage - Jalur Sukses
    public function test_store_sukses_data_valid_mencapai_blok_create(): void
    {
        $controller = new ItemCategoryController();
        $request = Request::create('/api/item-categories', 'POST', [
            'name'         => 'Rem',
            'descriptions' => 'Sparepart pengereman',
        ]);
        $user = new User(['employees_id' => null]);
        $request->setUserResolver(fn() => $user);

        $response = $controller->store($request);

        $this->assertEquals(201, $response->getStatusCode());
        $data = json_decode($response->getContent(), true);
        $this->assertEquals('success', $data['status']);
        $this->assertEquals('Kategori baru berhasil ditambahkan!', $data['message']);
        $this->assertDatabaseHas('item_categories', ['name' => 'Rem']);
    }

    // TC-KTR-05 - Statement Coverage - Jalur Gagal Validasi
    public function test_store_gagal_name_null_masuk_blok_validasi_error(): void
    {
        $controller = new ItemCategoryController();
        $request = Request::create('/api/item-categories', 'POST', [
            'name'         => null,
            'descriptions' => null,
        ]);
        $user = new User(['employees_id' => null]);
        $request->setUserResolver(fn() => $user);

        $this->expectException(\Illuminate\Validation\ValidationException::class);
        $controller->store($request);
    }

    // TC-KTR-NEW-01 - Branch Coverage - Name Duplikat
    public function test_store_gagal_name_duplikat_branch_unique(): void
    {
        $this->makeCategory('Aksesori Eksterior', 'Semua item body part');

        $controller = new ItemCategoryController();
        $request = Request::create('/api/item-categories', 'POST', [
            'name'         => 'Aksesori Eksterior',
            'descriptions' => 'Coba duplikat',
        ]);
        $user = new User(['employees_id' => null]);
        $request->setUserResolver(fn() => $user);

        $this->expectException(\Illuminate\Validation\ValidationException::class);
        $controller->store($request);

        $this->assertDatabaseCount('item_categories', 1);
    }

    // TC-KTR-NEW-02 - BVA - Name 255 Karakter (Valid)
    public function test_store_sukses_name_tepat_255_karakter(): void
    {
        $controller = new ItemCategoryController();
        $request = Request::create('/api/item-categories', 'POST', [
            'name'         => str_repeat('A', 255),
            'descriptions' => 'Tes BVA batas valid',
        ]);
        $user = new User(['employees_id' => null]);
        $request->setUserResolver(fn() => $user);

        $response = $controller->store($request);

        $this->assertEquals(201, $response->getStatusCode());
        $this->assertDatabaseCount('item_categories', 1);
    }

    // TC-KTR-NEW-03 - BVA - Name 256 Karakter (Invalid)
    public function test_store_gagal_name_256_karakter_branch_max_exceeded(): void
    {
        $controller = new ItemCategoryController();
        $request = Request::create('/api/item-categories', 'POST', [
            'name'         => str_repeat('A', 256),
            'descriptions' => 'Tes BVA batas invalid',
        ]);
        $user = new User(['employees_id' => null]);
        $request->setUserResolver(fn() => $user);

        $this->expectException(\Illuminate\Validation\ValidationException::class);
        $controller->store($request);
    }

    // TC-KTR-NEW-04 - Branch Coverage - Descriptions Nullable
    public function test_store_sukses_tanpa_descriptions_branch_nullable(): void
    {
        $controller = new ItemCategoryController();
        $request = Request::create('/api/item-categories', 'POST', [
            'name'         => 'Mesin Turbo',
            'descriptions' => null,
        ]);
        $user = new User(['employees_id' => null]);
        $request->setUserResolver(fn() => $user);

        $response = $controller->store($request);

        $this->assertEquals(201, $response->getStatusCode());
        $this->assertDatabaseHas('item_categories', ['name' => 'Mesin Turbo', 'descriptions' => null]);
    }

    // TC-KTR-NEW-05 - Branch Coverage - Update Nama Sendiri
    public function test_update_sukses_name_milik_record_sendiri(): void
    {
        $category = $this->makeCategory('Transmisi', 'Manual/Auto');

        $controller = new ItemCategoryController();
        $request = Request::create("/api/item-categories/{$category->category_id}", 'PUT', [
            'name'         => 'Transmisi',
            'descriptions' => 'Deskripsi diperbarui',
        ]);
        $user = new User(['employees_id' => null]);
        $request->setUserResolver(fn() => $user);

        $response = $controller->update($request, $category->category_id);

        $this->assertEquals(200, $response->getStatusCode());
        $data = json_decode($response->getContent(), true);
        $this->assertEquals('success', $data['status']);
        $this->assertEquals('Kategori berhasil diupdate!', $data['message']);
    }

    // TC-KTR-NEW-06 - Statement Coverage - Destroy
    public function test_destroy_sukses_eksekusi_mencapai_blok_delete(): void
    {
        $category = $this->makeCategory('Oli', 'Pelumas mesin');

        $controller = new ItemCategoryController();
        $response   = $controller->destroy($category->category_id);

        $this->assertEquals(200, $response->getStatusCode());
        $data = json_decode($response->getContent(), true);
        $this->assertEquals('success', $data['status']);
        $this->assertEquals('Kategori berhasil dihapus!', $data['message']);
        $this->assertDatabaseMissing('item_categories', ['category_id' => $category->category_id]);
    }

    // TC-KTR-NEW-07 - Branch Coverage - Show ID Tidak Ada
    public function test_show_gagal_id_tidak_ditemukan_branch_findorfail(): void
    {
        $controller = new ItemCategoryController();

        $this->expectException(\Illuminate\Database\Eloquent\ModelNotFoundException::class);
        $controller->show(99999);
    }

    // TC-KTR-NEW-08 - Branch Coverage - Index Dengan Search
    public function test_index_dengan_search_branch_pencarian_aktif(): void
    {
        $this->makeCategory('Filter Oli', 'Filter mesin');
        $this->makeCategory('Ban Luar', 'Ban kendaraan');

        $controller = new ItemCategoryController();
        $request    = Request::create('/api/item-categories', 'GET', ['search' => 'Filter']);

        $response = $controller->index($request);

        $this->assertEquals(200, $response->getStatusCode());
        $data = json_decode($response->getContent(), true);
        $this->assertCount(1, $data['data']);
        $this->assertEquals('Filter Oli', $data['data'][0]['name']);
    }

    // TC-KTR-NEW-09 - Branch Coverage - Index Tanpa Search
    public function test_index_tanpa_search_branch_pencarian_tidak_aktif(): void
    {
        $this->makeCategory('Aki', 'Baterai');
        $this->makeCategory('Kopling', 'Kopling manual');

        $controller = new ItemCategoryController();
        $request    = Request::create('/api/item-categories', 'GET');

        $response = $controller->index($request);

        $this->assertEquals(200, $response->getStatusCode());
        $data = json_decode($response->getContent(), true);
        $this->assertEquals(2, $data['total']);
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }
}