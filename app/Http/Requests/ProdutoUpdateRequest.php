<?php

namespace App\Http\Requests;

use App\Enums\Categoria;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

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
            'nome' => ['required', 'string', 'max:255'],
            'preco' => ['required', 'string', 'max:255'],
            'descricao' => ['required', 'text'],
            'foto' => ['nullable', 'image', 'max:2048'],
            'categoria' => ['required', 'string', Rule::in(Categoria::cases())],
        ];
    }
}
