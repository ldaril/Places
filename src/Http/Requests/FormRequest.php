<?php
namespace TypiCMS\Modules\Places\Http\Requests;

use TypiCMS\Http\Requests\AbstractFormRequest;

class FormRequest extends AbstractFormRequest {

    public function rules()
    {
        $rules = [
            'email'   => 'email',
            'website' => 'url',
            'image'   => 'image|max:2000|image_size:>=500',
        ];
        foreach (config('translatable.locales') as $locale) {
            $rules[$locale . '.slug'] = [
                'required_with:' . $locale . '.title',
                'required_if:' . $locale . '.status,1',
                'alpha_dash',
                'max:255',
            ];
            $rules[$locale . '.title'] = 'max:255';
        }
        return $rules;
    }

    /**
     * Sanitize inputs
     * 
     * @return array
     */
    public function sanitize()
    {
        $input = $this->all();

        // Checkboxes
        foreach (config('translatable.locales') as $locale) {
            $input[$locale]['status'] = $this->has($locale . '.status');
        }

        $this->replace($input);
        return $this->all();
    }
}
