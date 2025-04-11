<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePostRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // Only authenticated users can create posts
        return auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title' => [
                'required',
                'string',
                'min:3',
                'max:255',
                'unique:posts,title',
                'regex:/^[\w\s\-\.,:;!?()\[\]{}"]+$/u', // Allow alphanumeric, spaces, and common punctuation
            ],
            'description' => [
                'required',
                'string',
                'min:10',
                'max:10000', // Reasonable maximum length
            ],
            'creator' => [
                'required',
                'integer',
                'exists:users,id',
            ],
            'image' => [
                'nullable',
                'image',
                'mimes:jpg,png',
                'max:2048', // 2MB max size
            ]
        ];
    }

    public function messages(): array
    {
        return [
            'title.required' => 'Please provide a title for your post.',
            'title.string' => 'The title must be text.',
            'title.min' => 'The title should be at least 3 characters long.',
            'title.max' => 'The title cannot exceed 255 characters.',
            'title.unique' => 'This title is already in use. Please choose a different one.',
            'title.regex' => 'The title contains invalid characters.',
            
            'description.required' => 'Please provide a description for your post.',
            'description.string' => 'The description must be text.',
            'description.min' => 'The description should be at least 10 characters long.',
            'description.max' => 'The description is too long. Please keep it under 10,000 characters.',
            
            'creator.required' => 'Please select an author for this post.',
            'creator.integer' => 'The author ID must be a valid number.',
            'creator.exists' => 'The selected author does not exist in our system.',
            
            'image.image' => 'The uploaded file must be an image.',
            'image.mimes' => 'Only JPG and PNG image formats are allowed.',
            'image.max' => 'The image size must not exceed 2MB.'
        ];
    }
}
