<?php

return [

    'accepted' => 'Kolom :attribute harus diterima.',
    'accepted_if' => 'Kolom :attribute harus diterima ketika :other adalah :value.',
    'active_url' => 'Kolom :attribute bukan URL yang valid.',
    'after' => 'Kolom :attribute harus berisi tanggal setelah :date.',
    'after_or_equal' => 'Kolom :attribute harus berisi tanggal setelah atau sama dengan :date.',
    'alpha' => 'Kolom :attribute hanya boleh berisi huruf.',
    'alpha_dash' => 'Kolom :attribute hanya boleh berisi huruf, angka, strip, dan garis bawah.',
    'alpha_num' => 'Kolom :attribute hanya boleh berisi huruf dan angka.',
    'any_of' => 'Kolom :attribute tidak valid.',
    'array' => 'Kolom :attribute harus berupa array.',
    'ascii' => 'Kolom :attribute hanya boleh berisi karakter alfanumerik satu byte dan simbol.',
    'before' => 'Kolom :attribute harus berisi tanggal sebelum :date.',
    'before_or_equal' => 'Kolom :attribute harus berisi tanggal sebelum atau sama dengan :date.',

    'between' => [
        'array' => 'Kolom :attribute harus memiliki antara :min dan :max item.',
        'file' => 'Kolom :attribute harus berukuran antara :min dan :max kilobita.',
        'numeric' => 'Kolom :attribute harus bernilai antara :min dan :max.',
        'string' => 'Kolom :attribute harus berisi antara :min dan :max karakter.',
    ],

    'boolean' => 'Kolom :attribute harus bernilai true atau false.',
    'can' => 'Kolom :attribute mengandung nilai yang tidak diizinkan.',
    'confirmed' => 'Konfirmasi :attribute tidak cocok.',
    'contains' => 'Kolom :attribute tidak mengandung nilai yang diperlukan.',
    'current_password' => 'Password salah.',
    'date' => 'Kolom :attribute bukan tanggal yang valid.',
    'date_equals' => 'Kolom :attribute harus sama dengan tanggal :date.',
    'date_format' => 'Kolom :attribute harus sesuai format :format.',
    'decimal' => 'Kolom :attribute harus memiliki :decimal angka desimal.',
    'declined' => 'Kolom :attribute harus ditolak.',
    'declined_if' => 'Kolom :attribute harus ditolak ketika :other adalah :value.',
    'different' => 'Kolom :attribute dan :other harus berbeda.',
    'digits' => 'Kolom :attribute harus terdiri dari :digits digit.',
    'digits_between' => 'Kolom :attribute harus terdiri dari :min sampai :max digit.',
    'dimensions' => 'Dimensi gambar :attribute tidak valid.',
    'distinct' => 'Kolom :attribute memiliki nilai duplikat.',
    'doesnt_contain' => 'Kolom :attribute tidak boleh mengandung: :values.',
    'doesnt_end_with' => 'Kolom :attribute tidak boleh diakhiri dengan: :values.',
    'doesnt_start_with' => 'Kolom :attribute tidak boleh diawali dengan: :values.',
    'email' => 'Kolom :attribute harus berupa alamat email yang valid.',
    'encoding' => 'Kolom :attribute harus dikodekan dengan :encoding.',
    'ends_with' => 'Kolom :attribute harus diakhiri dengan salah satu dari: :values.',
    'enum' => 'Pilihan :attribute tidak valid.',
    'exists' => 'Pilihan :attribute tidak valid.',
    'extensions' => 'Kolom :attribute harus memiliki ekstensi: :values.',
    'file' => 'Kolom :attribute harus berupa file.',
    'filled' => 'Kolom :attribute harus memiliki nilai.',

    'gt' => [
        'array' => 'Kolom :attribute harus memiliki lebih dari :value item.',
        'file' => 'Kolom :attribute harus lebih besar dari :value kilobita.',
        'numeric' => 'Kolom :attribute harus lebih besar dari :value.',
        'string' => 'Kolom :attribute harus lebih dari :value karakter.',
    ],

    'gte' => [
        'array' => 'Kolom :attribute harus memiliki :value item atau lebih.',
        'file' => 'Kolom :attribute harus lebih besar atau sama dengan :value kilobita.',
        'numeric' => 'Kolom :attribute harus lebih besar atau sama dengan :value.',
        'string' => 'Kolom :attribute harus lebih besar atau sama dengan :value karakter.',
    ],

    'hex_color' => 'Kolom :attribute harus berupa warna heksadesimal yang valid.',
    'image' => 'Kolom :attribute harus berupa gambar.',
    'in' => 'Pilihan :attribute tidak valid.',
    'in_array' => 'Kolom :attribute tidak terdapat dalam :other.',
    'in_array_keys' => 'Kolom :attribute harus memiliki salah satu kunci berikut: :values.',
    'integer' => 'Kolom :attribute harus berupa bilangan bulat.',
    'ip' => 'Kolom :attribute harus berupa alamat IP yang valid.',
    'ipv4' => 'Kolom :attribute harus berupa alamat IPv4 yang valid.',
    'ipv6' => 'Kolom :attribute harus berupa alamat IPv6 yang valid.',
    'json' => 'Kolom :attribute harus berupa JSON yang valid.',
    'list' => 'Kolom :attribute harus berupa list.',
    'lowercase' => 'Kolom :attribute harus berupa huruf kecil.',

    'lt' => [
        'array' => 'Kolom :attribute harus memiliki kurang dari :value item.',
        'file' => 'Kolom :attribute harus kurang dari :value kilobita.',
        'numeric' => 'Kolom :attribute harus kurang dari :value.',
        'string' => 'Kolom :attribute harus kurang dari :value karakter.',
    ],

    'lte' => [
        'array' => 'Kolom :attribute tidak boleh lebih dari :value item.',
        'file' => 'Kolom :attribute harus kurang dari atau sama dengan :value kilobita.',
        'numeric' => 'Kolom :attribute harus kurang dari atau sama dengan :value.',
        'string' => 'Kolom :attribute harus kurang dari atau sama dengan :value karakter.',
    ],

    'mac_address' => 'Kolom :attribute harus berupa alamat MAC yang valid.',

    'max' => [
        'array' => 'Kolom :attribute tidak boleh memiliki lebih dari :max item.',
        'file' => 'Kolom :attribute tidak boleh lebih dari :max kilobita.',
        'numeric' => 'Kolom :attribute tidak boleh lebih dari :max.',
        'string' => 'Kolom :attribute tidak boleh lebih dari :max karakter.',
    ],

    'max_digits' => 'Kolom :attribute tidak boleh lebih dari :max digit.',
    'mimes' => 'Kolom :attribute harus berupa file dengan tipe: :values.',
    'mimetypes' => 'Kolom :attribute harus berupa file dengan tipe: :values.',

    'min' => [
        'array' => 'Kolom :attribute harus memiliki minimal :min item.',
        'file' => 'Kolom :attribute harus minimal :min kilobita.',
        'numeric' => 'Kolom :attribute harus minimal :min.',
        'string' => 'Kolom :attribute harus minimal :min karakter.',
    ],

    'min_digits' => 'Kolom :attribute harus memiliki minimal :min digit.',
    'missing' => 'Kolom :attribute harus tidak ada.',
    'multiple_of' => 'Kolom :attribute harus kelipatan dari :value.',
    'not_in' => 'Pilihan :attribute tidak valid.',
    'numeric' => 'Kolom :attribute harus berupa angka.',
    'present' => 'Kolom :attribute harus ada.',
    'regex' => 'Format kolom :attribute tidak valid.',
    'required' => 'Kolom :attribute wajib diisi.',
    'same' => 'Kolom :attribute harus sama dengan :other.',

    'string' => 'Kolom :attribute harus berupa teks.',
    'timezone' => 'Kolom :attribute harus berupa zona waktu yang valid.',
    'unique' => 'Kolom :attribute sudah digunakan.',
    'uploaded' => 'Kolom :attribute gagal diunggah.',
    'uppercase' => 'Kolom :attribute harus berupa huruf besar.',
    'url' => 'Kolom :attribute harus berupa URL yang valid.',
    'uuid' => 'Kolom :attribute harus berupa UUID yang valid.',

    'custom' => [
        'attribute-name' => [
            'rule-name' => 'pesan kustom',
        ],
    ],

    'attributes' => [],
];
