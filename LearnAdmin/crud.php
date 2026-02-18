<?php
require_once("db.php");

function insert($table, $data)
{
    global $conn;

    $columns = implode(",", array_keys($data));
    $values  = ":" . implode(",:", array_keys($data));

    $sql = "INSERT INTO $table ($columns) VALUES ($values)";
    $stmt = $conn->prepare($sql);

    return $stmt->execute($data);
}

function fetch_data($table, $condition = "")
{
    global $conn;

    $sql = "SELECT * FROM $table";
    if ($condition != "") {
        $sql .= " WHERE $condition";
    }

    $stmt = $conn->prepare($sql);
    $stmt->execute();

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function update_record($table, $data, $condition)
{
    global $conn;

    $fields = [];
    foreach ($data as $key => $value) {
        $fields[] = "$key=:$key";   //stores data in a column = named placeholder(value)
    }

    $field_data = implode(",", $fields);

    $sql = "UPDATE $table SET $field_data WHERE $condition";
    $stmt = $conn->prepare($sql);

    return $stmt->execute($data);
}

function delete_record($table, $condition)
{
    global $conn;

    $sql = "DELETE FROM $table WHERE $condition";
    $stmt = $conn->prepare($sql);

    return $stmt->execute();
}

// function login($table, $email, $password)
// {
//     global $conn;

//     $sql = "SELECT * FROM $table WHERE email=:email LIMIT 1";
//     $stmt = $conn->prepare($sql);
//     $stmt->execute(["email" => $email]);

//     $user = $stmt->fetch(PDO::FETCH_ASSOC);

//     if ($user && $password == $user['password']) {
//         $_SESSION['user'] = $user['name'];
//         return true;
//     }

//     return false;
// }

function print_id($table){
    global $conn;
    $query = $conn->prepare("SELECT * FROM $table");
    $query->execute();
    $total_row = $query->rowCount();
    return $total_row;
}
