<?php
if (!function_exists('consultaCep')) {
    function consultaCep(string $cep)
    {
        $cep = preg_replace('/\D/', '', $cep);

        $consultas = [
            [
                'url' => "https://viacep.com.br/ws/{$cep}/json/",
                'mapper' => static function (?object $resultado) {
                    return $resultado;
                },
            ],
            [
                'url' => "https://brasilapi.com.br/api/cep/v1/{$cep}",
                'mapper' => static function (?object $resultado) {
                    if (!$resultado) {
                        return null;
                    }

                    return (object) [
                        'cep' => $resultado->cep ?? null,
                        'logradouro' => $resultado->street ?? null,
                        'complemento' => $resultado->neighborhood ?? null,
                        'bairro' => $resultado->neighborhood ?? null,
                        'localidade' => $resultado->city ?? null,
                        'uf' => $resultado->state ?? null,
                    ];
                },
            ],
        ];

        foreach ($consultas as $consulta) {
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $consulta['url']);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
            curl_setopt($ch, CURLOPT_TIMEOUT, 10);

            $json = curl_exec($ch);
            $httpCode = (int) curl_getinfo($ch, CURLINFO_HTTP_CODE);
            $curlError = curl_error($ch);
            curl_close($ch);

            if ($json === false || $curlError !== '') {
                continue;
            }

            if ($httpCode < 200 || $httpCode >= 300) {
                continue;
            }

            $resultado = json_decode($json);

            if (!is_object($resultado)) {
                continue;
            }

            if (property_exists($resultado, 'erro') && $resultado->erro) {
                return (object) ['erro' => true];
            }

            if (property_exists($resultado, 'errors') && !empty($resultado->errors)) {
                return (object) ['erro' => true];
            }

            $mapeado = $consulta['mapper']($resultado);
            if (is_object($mapeado)) {
                return $mapeado;
            }
        }

        return (object) ['servico_indisponivel' => true];
    }
}
?>