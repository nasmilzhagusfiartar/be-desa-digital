<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SocialAssistanceRecipientUpdateRequest extends FormRequest
{
   
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'social_assistance_id' => 'required|exists:social_assistances,id',
            'head_of_family_id'    => 'required|exists:head_of_families,id',
            'amount'               => 'required|numeric',
            'reason'               => 'required|string',
            'bank'                 => 'required|string|in:bri,bni,bca,mandiri',
            'account_number'       => 'required|string|max:20',
            'proof'                => 'nullable|url',
            'status'               => 'nullable|string|in:pending,approved,rejected',
        ];
    }

    /**
     * Alias nama atribut untuk pesan error yang lebih ramah.
     */
    public function attributes()
    {
        return [
            'social_assistance_id' => 'Bantuan Sosial',
            'head_of_family_id'    => 'Kepala Keluarga',
            'amount'               => 'Nominal',
            'reason'               => 'Alasan',
            'bank'                 => 'Bank',
            'account_number'       => 'Nomor Rekening',
            'proof'                => 'Bukti Penerimaan',
            'status'               => 'Status Pengajuan',
        ];
    }
}
