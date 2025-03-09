<?php
require_once "connection.php";

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');

$request_method = $_SERVER["REQUEST_METHOD"];

switch ($request_method) {
    case 'OPTIONS':
        http_response_code(204); // No content
        break;
    case 'GET':
        if (!empty($_GET["id"])) {
            $id = intval($_GET["id"]);
            get_dosen($id);
        } else {
            get_dosen();
        }
        break;
    case 'POST':
        if (!empty($_GET["id"])) {
            $id = intval($_GET["id"]);
            update_dosen($id);
        } else {
            insert_dosen();
        }
        break;
    case 'DELETE':
        if (!empty($_GET["id"])) {
            $id = intval($_GET["id"]);
            delete_dosen($id);
        } else {
            header("HTTP/1.0 400 Bad Request");
            echo json_encode(["status" => 0, "message" => "Missing ID"]);
        }
        break;
    default:
        header("HTTP/1.0 405 Method Not Allowed");
        echo json_encode(["status" => 0, "message" => "Method Not Allowed"]);
        break;
}

/**
 * Mengambil data dosen (semua atau berdasarkan ID)
 */
function get_dosen($id = 0)
{
    global $mysqli;
    $query = "SELECT * FROM dosen";
    if ($id != 0) {
        $query .= " WHERE id = " . $id . " LIMIT 1";
    }
    
    $data = [];
    $result = $mysqli->query($query);

    while ($row = $result->fetch_object()) {
        $data[] = $row;
    }

    $response = [
        'status' => 1,
        'message' => 'Data retrieved successfully',
        'data' => $data
    ];
    
    header('Content-Type: application/json');
    echo json_encode($response);
}

/**
 * Menambahkan dosen baru ke database
 */
function insert_dosen()
{
    global $mysqli;

    $data = !empty($_POST) ? $_POST : json_decode(file_get_contents('php://input'), true);

    if (!isset($data['nama'], $data['email'], $data['no_hp'])) {
        echo json_encode(["status" => 0, "message" => "Missing parameters"]);
        return;
    }

    $nama = $data['nama'];
    $email = $data['email'];
    $no_hp = $data['no_hp'];
    
    $query = "INSERT INTO dosen (nama, email, no_hp) VALUES ('$nama', '$email', '$no_hp')";
    $result = $mysqli->query($query);

    $response = $result
        ? ["status" => 1, "message" => "Dosen added successfully"]
        : ["status" => 0, "message" => "Failed to add dosen"];

    header('Content-Type: application/json');
    echo json_encode($response);
}

/**
 * Mengupdate data dosen berdasarkan ID
 */
function update_dosen($id)
{
    global $mysqli;

    $data = !empty($_POST) ? $_POST : json_decode(file_get_contents('php://input'), true);

    if (!isset($data['nama'], $data['email'], $data['no_hp'])) {
        echo json_encode(["status" => 0, "message" => "Missing parameters"]);
        return;
    }

    $nama = $data['nama'];
    $email = $data['email'];
    $no_hp = $data['no_hp'];
    
    $query = "UPDATE dosen SET nama = '$nama', email = '$email', no_hp = '$no_hp' WHERE id = $id";
    $result = $mysqli->query($query);

    $response = $result
        ? ["status" => 1, "message" => "Dosen updated successfully"]
        : ["status" => 0, "message" => "Failed to update dosen"];

    header('Content-Type: application/json');
    echo json_encode($response);
}

/**
 * Menghapus data dosen berdasarkan ID
 */
function delete_dosen($id)
{
    global $mysqli;
    $query = "DELETE FROM dosen WHERE id = $id";
    $result = $mysqli->query($query);

    $response = $result
        ? ["status" => 1, "message" => "Dosen deleted successfully"]
        : ["status" => 0, "message" => "Failed to delete dosen"];

    header('Content-Type: application/json');
    echo json_encode($response);
}


