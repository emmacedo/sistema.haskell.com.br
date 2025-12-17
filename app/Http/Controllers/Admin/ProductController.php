<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Lista todos os produtos
     */
    public function index(Request $request)
    {
        $query = Product::query();

        // Busca por nome
        if ($request->filled('search')) {
            $query->where('name', 'like', "%{$request->search}%");
        }

        // Filtro por status
        if ($request->filled('status')) {
            $query->where('active', $request->status === 'active');
        }

        $products = $query->ordered()->paginate(15);

        return view('admin.products.index', compact('products'));
    }

    /**
     * Formulário de criação
     */
    public function create()
    {
        return view('admin.products.create');
    }

    /**
     * Salva novo produto
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100|unique:products,name',
            'active' => 'boolean',
            'order' => 'nullable|integer|min:0',
        ], [
            'name.required' => 'O nome do produto é obrigatório.',
            'name.unique' => 'Já existe um produto com este nome.',
            'name.max' => 'O nome não pode ter mais de 100 caracteres.',
        ]);

        $validated['active'] = $request->has('active');
        $validated['order'] = $validated['order'] ?? 0;

        try {
            Product::create($validated);

            return redirect()
                ->route('admin.products.index')
                ->with('success', 'Produto criado com sucesso!');
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Erro ao criar produto: ' . $e->getMessage());
        }
    }

    /**
     * Formulário de edição
     */
    public function edit(Product $product)
    {
        return view('admin.products.edit', compact('product'));
    }

    /**
     * Atualiza produto
     */
    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100|unique:products,name,' . $product->id,
            'active' => 'boolean',
            'order' => 'nullable|integer|min:0',
        ], [
            'name.required' => 'O nome do produto é obrigatório.',
            'name.unique' => 'Já existe um produto com este nome.',
            'name.max' => 'O nome não pode ter mais de 100 caracteres.',
        ]);

        $validated['active'] = $request->has('active');
        $validated['order'] = $validated['order'] ?? 0;

        try {
            $product->update($validated);

            return redirect()
                ->route('admin.products.index')
                ->with('success', 'Produto atualizado com sucesso!');
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Erro ao atualizar produto: ' . $e->getMessage());
        }
    }

    /**
     * Remove produto
     */
    public function destroy(Product $product)
    {
        try {
            // Verificar se há mensagens de contato vinculadas
            if ($product->contactMessages()->exists()) {
                return redirect()
                    ->route('admin.products.index')
                    ->with('error', 'Não é possível excluir este produto pois existem mensagens de contato vinculadas.');
            }

            $product->delete();

            return redirect()
                ->route('admin.products.index')
                ->with('success', 'Produto excluído com sucesso!');
        } catch (\Exception $e) {
            return redirect()
                ->route('admin.products.index')
                ->with('error', 'Erro ao excluir produto: ' . $e->getMessage());
        }
    }

    /**
     * Alterna status ativo/inativo
     */
    public function toggleStatus(Product $product)
    {
        $product->update(['active' => !$product->active]);

        $status = $product->active ? 'ativado' : 'desativado';

        return redirect()
            ->route('admin.products.index')
            ->with('success', "Produto {$status} com sucesso!");
    }
}
