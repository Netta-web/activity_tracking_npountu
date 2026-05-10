<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreActivityRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->isAdmin();
    }

    public function rules(): array
    {
        return [
            'title'       => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:5000'],
            'category'    => ['required', 'string', 'max:100'],
            'priority'    => ['required', 'in:low,medium,high,critical'],
            'assigned_to' => ['nullable', 'exists:users,id'],
            'due_date'    => ['nullable', 'date', 'after_or_equal:today'],
        ];
    }

    public function messages(): array
    {
        return [
            'title.required'    => 'Activity title is required.',
            'category.required' => 'Please select a category.',
            'priority.required' => 'Please select a priority level.',
            'due_date.after_or_equal' => 'Due date must be today or in the future.',
        ];
    }
}
