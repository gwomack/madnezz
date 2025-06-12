<?php

declare(strict_types = 1);

namespace App\Http\Requests;

use App\Enums\Categoria;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProdutoUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'nome'      => ['required', 'string', 'max:255'],
            'preco'     => ['required', 'numeric', 'min:0.01'],
            'descricao' => ['nullable', 'string'],
            'foto'      => ['nullable', 'mimes:jpeg,jpg,png'],
            'categoria' => ['required', 'string', Rule::in(Categoria::cases())],
        ];
    }

    public function messages(): array
    {
        return [
            'nome.required'      => 'O campo :attribute é obrigatório.',
            'preco.required'     => 'O campo :attribute é obrigatório.',
            'preco.min'          => 'O :attribute precisa ser maior que :min.',
            'descricao.required' => 'O campo :attribute é obrigatório.',
            'foto.image'         => 'O campo :attribute deve ser uma imagem.',
            'foto.max'           => 'O campo :attribute deve ter no máximo :max KB.',
            'categoria.required' => 'O campo :attribute é obrigatório.',
            'categoria.in'       => 'O campo :attribute deve ser uma categoria válida.',
        ];
    }
}
