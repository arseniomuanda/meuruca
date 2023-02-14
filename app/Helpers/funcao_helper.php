<?php
function cleanarray($data)
{
    $newdata = null;
    foreach ($data as $key => $val) {
        if ($val !== null) {
            $newdata[$key] = $val;
        }
    }
    return $newdata;
}

function validar_login($email, $db)
{
    $query = $db->query("SELECT COUNT(*) AS total FROM utilizadors WHERE email = '$email'")->getRow(0)->total;
    if ($query > 0) {
        $data = $db->query("SELECT utilizadors.*, proprietarios.nome porfilename FROM utilizadors INNER JOIN proprietarios ON utilizadors.proprietario=proprietarios.id INNER JOIN contas ON contas.id=proprietarios.conta WHERE utilizadors.email = '$email';")->getRow(0);
    } else {
        $data = null;
    }
    return $data;
}

function cadastronormal($model, $data, $db, $auditoria)
{
    $query = $model->save($data);

    if ($query) {
        $id = $db->insertID();
        $auditoria->save([
            'accao' => 'Inserir',
            'processo' => "New $model->table",
            'registo' => $id,
            'utilizador' => $data['criadopor'],
            'dataAcao' => date('Y-m-d H:i:s'),
            'dataExpiracao' => date('Y-m-d H:i:s', strtotime('+2 years', strtotime(date('Y-m-d H:i:s')))),
        ]);
        return [
            'message' => 'Sucesso!',
            'error' => false,
            'code' => 200,
            'id' => $id,
            'data' => $model->where('id', $id)->paginate()
        ];
    } else if ($model->errors()) {
        $message = $model->errors();
    } else {
        $message = 'Sem sucesso';
    }

    return [
        'message' => $message,
        'error' => false,
        'code' => 400,
    ];
}

function cadastrocomumafoto($model, $data, $db, $auditoria, $foto, $campo)
{
    $query = $model->save($data);

    if ($query) {
        $id = $db->insertID();

        if (is_file($foto)) {
            $nome = (int)$id . '.' . $foto->getExtension();
            if (store($foto, $nome, $model->table) !== true) {
                $output = [
                    'message' => 'Não foi possivel salvar a foto',
                    'error' => true,
                    'type' => $model->erros()
                ];
                return $output;
            }
            $path = base_url() . "/file/$model->table/$nome";
            $db->query("UPDATE $model->table SET $campo = '$path' WHERE `id` = $id");
        }
        $auditoria->save([
            'accao' => 'Inserir',
            'processo' => "New $model->table",
            'registo' => $id,
            'utilizador' => $data['criadopor'],
            'dataAcao' => date('Y-m-d H:i:s'),
            'dataExpiracao' => date('Y-m-d H:i:s', strtotime('+2 years', strtotime(date('Y-m-d H:i:s')))),
        ]);
        return [
            'message' => 'Sucesso!',
            'error' => false,
            'code' => 200,
            'data' => $model->where('id', $id)->paginate()
        ];
    } else if ($model->errors()) {
        $message = $model->errors();
    } else {
        $message = 'Sem sucesso';
    }

    return [
        'message' => $message,
        'error' => false,
        'code' => 400,
    ];
}

function cadastrocomduasfotos($model, $data, $db, $auditoria, $precesso, $tabela, $foto1, $campo1, $foto2, $campo2)
{
    $query = $model->save($data);

    if ($query) {
        $id = $db->insertID();

        if (is_file($foto1)) {
            $nome1 = $campo1 . (int)$id . '.' . $foto1->getExtension();
            if (store($foto1, $nome1, $tabela) !== true) {
                $output = [
                    'message' => 'Não foi possivel salvar a foto1',
                    'error' => true,
                    'type' => $model->erros()
                ];
                return $output;
            }
            $path = base_url() . "/file/$tabela/$nome1";
            $db->query("UPDATE $tabela SET $campo1 = '$path' WHERE `id` = $id");
        }

        if (is_file($foto2)) {
            $nome2 = $campo2 . (int)$id . '.' . $foto2->getExtension();
            if (store($foto2, $nome2, $tabela) !== true) {
                $output = [
                    'message' => 'Não foi possivel salvar a foto2',
                    'error' => true,
                    'type' => $model->erros()
                ];
                return $output;
            }
            $path = base_url() . "/file/$tabela/$nome2";
            $db->query("UPDATE $tabela SET $campo2 = '$path' WHERE `id` = $id");
        }
        $auditoria->save([
            'accao' => 'Inserir',
            'processo' => $precesso,
            'registo' => $id,
            'utilizador' => $data['criadopor'],
            'dataAcao' => date('Y-m-d H:i:s'),
            'dataExpiracao' => date('Y-m-d H:i:s', strtotime('+2 years', strtotime(date('Y-m-d H:i:s')))),
        ]);
        return [
            'message' => 'Sucesso!',
            'error' => false,
            'code' => 200,
            'data' => $model->where('id', $id)->paginate()
        ];
    } else if ($model->errors()) {
        $message = $model->errors();
    } else {
        $message = 'Sem sucesso';
    }

    return [
        'message' => $message,
        'error' => false,
        'code' => 400,
    ];
}

function daletarnormal($data, $db, $model, $auditoria)
{
    $query = $db->query("DELETE FROM $model->table WHERE `id` = " . $data['id']);
    if ($query) {
        $auditoria->save([
            'accao' => 'Deletar',
            'processo' => 'Deletar ' . $model->table,
            'registo' => $data['id'],
            'utilizador' => $data['criadopor'],
            'dataAcao' => date('Y-m-d'),
            'dataExpiracao' => date("Y-m-d H:i:s", strtotime("+2 years", strtotime(date("Y-m-d H:i:s")))),
        ]);
        return [
            'error' => false,
            'code' => 200,
            'message' => 'Successfull'
        ];
    }
    return [
        'error' => true,
        'code' => 400,
        'message' => 'Error!'
    ];
}

function deletarItemAgenda($data, $db, $model, $auditoria)
{
    $agenda = $data['agenda'];
    $id = $data['id'];
    $fatura = $db->query("SELECT * FROM facturas WHERE agenda = $agenda")->getRow(0)->id;
    $query = $db->query("DELETE FROM $model->table WHERE `itemId` = $id  AND `factura` = $fatura");
    if ($query) {
        $auditoria->save([
            'accao' => 'Deletar',
            'processo' => 'Deletar ' . $model->table,
            'registo' => $data['id'],
            'utilizador' => $data['criadopor'],
            'dataAcao' => date('Y-m-d'),
            'dataExpiracao' => date("Y-m-d H:i:s", strtotime("+2 years", strtotime(date("Y-m-d H:i:s")))),
        ]);
        return [
            'error' => false,
            'code' => 200,
            'message' => 'Successfull'
        ];
    }
    return [
        'error' => true,
        'code' => 400,
        'message' => 'Error!'
    ];
}

function daletarespecial($id, $actor, $db, $model, $auditoria)
{
    $query = $db->query("DELETE FROM $model->table WHERE `id` = $id");
    if ($query) {
        $auditoria->save([
            'accao' => 'Deletar',
            'processo' => 'Deletar ' . $model->table,
            'registo' => $id,
            'utilizador' => $actor,
            'dataAcao' => date('Y-m-d'),
            'dataExpiracao' => date("Y-m-d H:i:s", strtotime("+2 years", strtotime(date("Y-m-d H:i:s")))),
        ]);
        return [
            'error' => false,
            'code' => 200,
            'message' => 'Successfull'
        ];
    }
    return [
        'error' => true,
        'code' => 400,
        'message' => 'Error!'
    ];
}

function deletecomumafoto($id, $db, $table, $actor, $auditoria, $precesso, $tabela, $path)
{
    $query = $db->query("DELETE FROM $table WHERE `id` = $id");
    if ($query) {
        $auditoria->save([
            'accao' => 'Deletar',
            'processo' => 'Deletar ' . $precesso,
            'registo' => $id,
            'utilizador' => $actor,
            'dataAcao' => date('Y-m-d'),
            'dataExpiracao' => date("Y-m-d H:i:s", strtotime("+2 years", strtotime(date("Y-m-d H:i:s")))),
        ]);

        if (is_dir($_SERVER['DOCUMENT_ROOT'] . '/file/' . $tabela . '/')) {
            $path = str_replace(base_url(), '', $path);
            if (file_exists($_SERVER['DOCUMENT_ROOT'] . $path)) {
                unlink($_SERVER['DOCUMENT_ROOT'] . $path);
            }
        }

        return true;
    }
    return false;
}

function updatenomal($model, $data, $auditoria)
{
    $query = $model->save($data);

    if ($query) {
        $auditoria->save([
            'accao' => 'Actualizar',
            'processo' => 'Update ' . $model->table,
            'registo' => $data['id'],
            'utilizador' => $data['criadopor'],
            'dataAcao' => date('Y-m-d'),
            'dataExpiracao' => date("Y-m-d H:i:s", strtotime("+2 years", strtotime(date("Y-m-d H:i:s")))),
        ]);
        return [
            'message' => 'Sucesso!',
            'error' => false,
            'code' => 200,
            'data' => $model->where('id', $data['id'])->paginate()
        ];
    } else if ($model->errors()) {
        $message = $model->errors();
    } else {
        $message = 'Sem sucesso';
    }

    return [
        'message' => $message,
        'error' => false,
        'code' => 400,
    ];
}

function updatecomumafoto($model, $data, $db, $auditoria, $precesso, $tabela, $foto, $campo)
{
    $query = $model->save($data);

    if ($query) {
        $id = $data['id'];

        if (is_file($foto)) {
            $nome = (int)$id . '.' . $foto->getExtension();
            if (store($foto, $nome, $tabela) !== true) {
                $output = [
                    'message' => 'Não foi possivel salvar a foto',
                    'error' => true,
                    'type' => $model->erros()
                ];
                return $output;
            }
            $path = base_url() . "/file/$tabela/$nome";
            $db->query("UPDATE $tabela SET $campo = '$path' WHERE `id` = $id");
        }
        $auditoria->save([
            'accao' => 'Actualizar',
            'processo' => 'Actualizar ' . $precesso,
            'registo' => $data['id'],
            'utilizador' => $data['criadopor'],
            'dataAcao' => date('Y-m-d'),
            'dataExpiracao' => date("Y-m-d H:i:s", strtotime("+2 years", strtotime(date("Y-m-d H:i:s")))),
        ]);
        return [
            'message' => 'Sucesso!',
            'error' => false,
            'code' => 200,
            'data' => $model->where('id', $id)->paginate()
        ];
    } else if ($model->errors()) {
        $message = $model->errors();
    } else {
        $message = 'Sem sucesso';
    }

    return [
        'message' => $message,
        'error' => false,
        'code' => 400,
    ];
}

function updatecomduasfotos($model, $data, $db, $auditoria, $precesso, $tabela, $foto1, $campo1, $foto2, $campo2)
{
    $query = $model->save($data);

    if ($query) {
        $id = $data['id'];

        if (is_file($foto1)) {
            $nome1 = $campo1 . (int)$id . '.' . $foto1->getExtension();
            if (store($foto1, $nome1, $tabela) !== true) {
                $output = [
                    'message' => 'Não foi possivel salvar a foto1',
                    'error' => true,
                    'type' => $model->erros()
                ];
                return $output;
            }
            $path = base_url() . "/file/$tabela/$nome1";
            $db->query("UPDATE $tabela SET $campo1 = '$path' WHERE `id` = $id");
        }

        if (is_file($foto2)) {
            $nome2 = $campo2 . (int)$id . '.' . $foto2->getExtension();
            if (store($foto2, $nome2, $tabela) !== true) {
                $output = [
                    'message' => 'Não foi possivel salvar a foto2',
                    'error' => true,
                    'type' => $model->erros()
                ];
                return $output;
            }
            $path = base_url() . "/file/$tabela/$nome2";
            $db->query("UPDATE $tabela SET $campo2 = '$path' WHERE `id` = $id");
        }
        $auditoria->save([
            'accao' => 'Actualizar',
            'processo' => 'Actualizar ' . $precesso,
            'registo' => $data['id'],
            'utilizador' => $data['criadopor'],
            'dataAcao' => date('Y-m-d'),
            'dataExpiracao' => date("Y-m-d H:i:s", strtotime("+2 years", strtotime(date("Y-m-d H:i:s")))),
        ]);
        return [
            'message' => 'Sucesso!',
            'error' => false,
            'code' => 200,
            'data' => $model->where('id', $id)->paginate()
        ];
    } else if ($model->errors()) {
        $message = $model->errors();
    } else {
        $message = 'Sem sucesso';
    }

    return [
        'message' => $message,
        'error' => false,
        'code' => 400,
    ];
}

/**
 * $data = [email, password, actor]
 * function exclusive to change de user password
 */
function emailchange($data, $pessaoModel, $db, $auditoria)
{
    $query = $pessaoModel->where('email', $data['email'])->countAllResults();
    if ($query > 0) {
        $pessoa = $pessaoModel->where('email', $data['email'])->first();
        if (password_verify($data['password'], $pessoa['password'])) {
            $db->query('UPDATE `pessoas` SET `email` = ' . $data['new_email']);
            $auditoria->save([
                'accao' => 'Actualizar',
                'processo' => 'Alteração de email',
                'registo' => $data['id'],
                'utilizador' => $data['criadopor'],
                'dataAcao' => date('Y-m-d'),
                'dataExpiracao' => date("Y-m-d H:i:s", strtotime("+2 years", strtotime(date("Y-m-d H:i:s")))),
            ]);

            return [
                'code' => 202,
                'message' => 'Palavra pass, alterada com sucesso!',
                'error' => false
            ];
        } else
            #Erro de palavra pass
            return [
                'code' => 400,
                'message' => 'Palavra pass incorreta!',
                'error' => true
            ];
        #Erro de email
        return [
            'code' => 400,
            'message' => 'Email incorreto!',
            'error' => true
        ];
    }
}

function password_reset($data, $db, $auditoria)
{
    $hashedpass = password_hash('123456', PASSWORD_BCRYPT);
    $email = $data['email'];

    $db->query("UPDATE `pessoas` SET `password` = '$hashedpass' WHERE `email` = '$email'");

    $auditoria->save([
        'accao' => 'Reset',
        'processo' => 'Reset da palavra pass',
        'registo' => $data['id'],
        'utilizador' => $data['criadopor'],
        'dataAcao' => date('Y-m-d'),
        'dataExpiracao' => date("Y-m-d H:i:s", strtotime("+2 years", strtotime(date("Y-m-d H:i:s")))),
    ]);

    return [
        'code' => 202,
        'message' => 'Palavra pass reset com sucesso!',
        'error' => false
    ];
}

function store($file, $nome, $tabela)
{
    // $file = $this->request->getFile('logo');

    if (is_dir($_SERVER['DOCUMENT_ROOT'] . '/file/' . $tabela . '/')) {
        if (file_exists($_SERVER['DOCUMENT_ROOT'] . '/file/' . $tabela . '/' . $nome)) {
            unlink($_SERVER['DOCUMENT_ROOT'] . '/file/' . $tabela . '/' . $nome);
        }
    }
    if ($file->move($_SERVER['DOCUMENT_ROOT'] . '/file/' . $tabela . '', $nome)) {
        return true;
    }
    return false;
}

function rrmdir($src)
{
    $dir = opendir($src);
    while (false !== ($file = readdir($dir))) {
        if (($file != '.') && ($file != '..')) {
            $full = $src . '/' . $file;
            if (is_dir($full)) {
                rrmdir($full);
            } else {
                unlink($full);
            }
        }
    }
    closedir($dir);
    rmdir($src);
}

/* function auditoria($model, $actor, string $accao, string $processo, $registo)
{
    return $model->save([
        'accao' => $accao,
        'processo' => $processo,
        'registo' => $registo,
        'utilizador' => $actor,
        'dataAcao' => date('Y-m-d'),
        'dataExpiracao' => date("Y-m-d H:i:s", strtotime("+2 years", strtotime(date("Y-m-d H:i:s")))),
    ]);
    
}*/

//Funcçoes para lidar com o tempo.

function datadiff($momento, $format = '%a')
{
    $date1 = date_create(date("Y-m-d H:i:s"));
    $date2 = date_create($momento);
    $diff = date_diff($date1, $date2);
    $dia = $diff->format($format);

    if (strtotime(date("Y-m-d H:i:s")) > strtotime($momento)) {
        return "Atrasado!";
    } else {
        if ($dia > 1) return "daqui à " . $dia . " dias.";
        else if ($dia == 1) return "daqui à " . $dia . " dia.";
        else if ($dia == 0)
            return 'Hoje às ' .
                date('H', strtotime($momento)) . ':' .  date('i', strtotime($momento));
    }
}

function nextManutencao(
    $km_actual,
    $km_diaria_dias_semana,
    $km_diaria_final_semana,
    $data_ultima_revisao,
    $km_na_ultima_revisao,
    $periodo_de_revisao = 5000
) {
    $dias_laborais = (float)$km_diaria_dias_semana * 5;
    $final_de_semanda = (float) $km_diaria_final_semana * 2;

    $semanal = $dias_laborais + $final_de_semanda;
    $km_total = $km_actual - (float) $km_na_ultima_revisao;
    $dias = 0;
    do {
        $km_total += $semanal;
        $dias += 7;
    } while ($km_total <= $periodo_de_revisao);

    $resultado = date('Y-m-d', strtotime($data_ultima_revisao . " + $dias days"));
    return [
        'dias' => datadiff($resultado),
        'data' => $resultado,
        'mensagem' => 'O meu ruca sugere uma manutencão em ' . $resultado,
        'pecas' => 'Formas em desenvolvimento'
    ];
}

function returnVoid($data, $code, string $message = 'Pedido mal formado!')
{
    return [
        'Created_at' => "2022-03-29",
        'Time' => date('Y-m-d H:i:s'),
        'Autor' => "Arsénio Muanda",
        'Company' => 'Arpetic',
        'Project' => 'API GESTÃO DE VIATURAS',
        'Data' => $data,
        'code' => $code,
        'message' => $message
    ];
}

function cadastronormalFromJson($model, $data, $db)
{
    $query = $model->save($data);

    if ($query) {
        $id = $db->insertID();
        return [
            'message' => 'Sucesso!',
            'error' => false,
            'status' => 200,
            'data' => $model->where('id', $id)->paginate()
        ];
    } else if ($model->errors()) {
        $message = $model->errors();
    } else {
        $message = 'Sem sucesso';
    }

    return [
        'message' => $message,
        'error' => false,
        'status' => 400,
    ];
}


function newGuid()
{
    if (function_exists('com_create_guid') === true) {
        return trim(com_create_guid(), '{}');
    }

    $ar = Array();

    array_pop($ar);
    array_push($ar, 'dsf');

    return sprintf('%04X%04X-%04X-%04X-%04X-%04X%04X%04X', mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(16384, 20479), mt_rand(32768, 49151), mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(0, 65535));
}

function eliminarPedido($id, $auditoria, $criadopor)
{
    $db = Config\Database::connect();

    $auditoria->save([
        'accao' => 'Reset',
        'processo' => 'Reset da palavra pass',
        'registo' => $id,
        'utilizador' => $criadopor,
        'dataAcao' => date('Y-m-d'),
        'dataExpiracao' => date("Y-m-d H:i:s", strtotime("+2 years", strtotime(date("Y-m-d H:i:s")))),
    ]);

    $db->query("DELETE FROM itemfacturas WHERE factura = $id");
    $response = $db->query("DELETE FROM facturas WHERE id = $id");

    return $response;
}