<?php

function validarSenhaForte($senha)
{
    // mínimo 8 caracteres, pelo menos 1 maiúscula, 1 minúscula, 1 número e 1 caractere especial
    $regex = "/^(?=.*[A-Z])(?=.[a-z])(?=.\d)(?=.[@$!%?&])[A-Za-z\d@$!%?&]{8,}$/";
    return preg_match($regex, $senha);
}

function gerarUuid() {
    $data = random_bytes(16);
    $data[6] = chr(ord($data[6]) & 0x0f | 0x40); // versão 4
    $data[8] = chr(ord($data[8]) & 0x3f | 0x80); // variante
    return vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($data), 4));
}

?>
