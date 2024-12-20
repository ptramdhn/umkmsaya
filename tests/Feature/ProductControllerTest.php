<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Product;
use App\Models\Cart;
use App\Interfaces\ProductRepositoryInterface;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;

class ProductControllerTest extends TestCase
{
    use RefreshDatabase; // Untuk memastikan database di-reset setiap pengujian

    protected $mockProductRepository;

    protected function setUp(): void
    {
        parent::setUp();

        // Mocking ProductRepositoryInterface
        $this->mockProductRepository = $this->createMock(ProductRepositoryInterface::class);
        $this->app->instance(ProductRepositoryInterface::class, $this->mockProductRepository);
    }

    public function test_show_displays_product_view_with_correct_data()
    {
        // Mocking produk yang akan dikembalikan oleh repository
        $mockProduct = Product::factory()->make(['uid' => 'dummy-uid']);

        $this->mockProductRepository
            ->method('getProductByUid')
            ->with('dummy-uid')
            ->willReturn($mockProduct);

        $response = $this->get(route('product.show', ['uid' => 'dummy-uid']));

        // Periksa apakah view yang benar dikembalikan
        $response->assertStatus(200);
        $response->assertViewIs('pages.store.product');
        $response->assertViewHas('product', $mockProduct);
    }

    public function test_produkTerbaik_displays_best_product_view_with_correct_data()
    {
        // Mocking produk populer yang akan dikembalikan oleh repository
        $mockProducts = Product::factory()->count(3)->make();

        $this->mockProductRepository
            ->method('getProductPopular')
            ->willReturn($mockProducts);

        $response = $this->get(route('produkTerbaik'));

        // Periksa apakah view yang benar dikembalikan
        $response->assertStatus(200);
        $response->assertViewIs('pages.produkTerbaik');
        $response->assertViewHas('products', $mockProducts);
    }

    public function test_removeFromCart_removes_product_and_redirects()
    {
        // Mocking user dan produk
        $user = User::factory()->create();
        $product = Product::factory()->create();
        $cart = Cart::factory()->create([
            'user_id' => $user->id,
            'product_id' => $product->id,
        ]);

        $this->actingAs($user);

        // Simulasikan permintaan ke endpoint
        $response = $this->delete("/cart/remove/{$product->id}");

        // Periksa apakah produk dihapus dari keranjang
        $this->assertDatabaseMissing('carts', [
            'user_id' => $user->id,
            'product_id' => $product->id,
        ]);

        // Periksa redirect dengan pesan sukses
        $response->assertRedirect(route('keranjang.show', $product->store->slug));
        $response->assertSessionHas('status', 'success');
        $response->assertSessionHas('message', 'Produk Berhasil Dihapus Dari Keranjang');
    }

}
