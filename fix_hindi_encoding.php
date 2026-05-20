<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$fix = function ($v) {
    if (!is_string($v) || $v === '') {
        return $v;
    }

    $decoded = @mb_convert_encoding($v, 'UTF-8', 'ISO-8859-1');
    if (!is_string($decoded) || $decoded === $v) {
        return $v;
    }

    $decodedHasHi = preg_match('/[\x{0900}-\x{097F}]/u', $decoded) === 1;
    $origHasHi = preg_match('/[\x{0900}-\x{097F}]/u', $v) === 1;

    return ($decodedHasHi && ! $origHasHi) ? $decoded : $v;
};

$hFixed = 0;
App\Models\Hospital::query()->chunkById(200, function ($rows) use (&$hFixed, $fix) {
    foreach ($rows as $row) {
        $dirty = false;

        foreach (['name_hi', 'address_hi'] as $col) {
            $new = $fix($row->{$col});
            if ($new !== $row->{$col}) {
                $row->{$col} = $new;
                $dirty = true;
            }
        }

        if ($dirty) {
            $row->save();
            $hFixed++;
        }
    }
});

$bFixed = 0;
App\Models\BloodBank::query()->chunkById(200, function ($rows) use (&$bFixed, $fix) {
    foreach ($rows as $row) {
        $dirty = false;

        foreach (['name_hi', 'address_hi'] as $col) {
            $new = $fix($row->{$col});
            if ($new !== $row->{$col}) {
                $row->{$col} = $new;
                $dirty = true;
            }
        }

        if ($dirty) {
            $row->save();
            $bFixed++;
        }
    }
});

echo "hospitals_fixed={$hFixed}\n";
echo "bloodbanks_fixed={$bFixed}\n";
