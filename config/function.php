<?php 

// SESSION START
session_start();

// DATABASE FILE
require 'db.php';

// INPUT FIELD VALIDATION FUNCTION
function validate($inputData){
    global $conn;
    $validateData = mysqli_real_escape_string($conn , $inputData);
    return trim($validateData);
}

// REDIRECT FROM 1 PAGE TO ANOTHER PAGE WITH THE MESSAGE (STATUS)
function redirect($url , $status){
    $_SESSION['status'] = $status;
    header('Location: ' . $url);
    exit(0);
}

// DISPLAY MESSAGES OR STATUS AFTER ANY PROCESS
function alertMessage(){
    if(isset($_SESSION['status'])){
        
        echo '<div class="alert alert-warning alert-dismissible fade show" role="alert">
            <h6> '.$_SESSION['status'].' </h6>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>'; // Boostrap Alert
        
        unset($_SESSION['status']);
    }
}

// CREATE RECORD USING THIS FUNCTION
function insert($tableName , $data){
    global $conn;
    $table = validate($tableName);
    
    $columns = array_keys($data); // yeah Array ki Key nikalta hai
    $values = array_values($data); // yeah Array ki Value nikalta hai

    $finalColumn = implode(',' , $columns);
    $finalValues = "'" . implode("', '", $values) . "'";

    $query = "INSERT INTO $table ($finalColumn) VALUES ($finalValues)";
    $result = mysqli_query($conn , $query);
    return $result;
}

// UPDATE DATA USING THIS FUNCTION
function update($tableName , $id , $data){
    global $conn;

    $table = validate($tableName);
    $id = validate($id);
    
    $updateDataString = "";

    foreach($data as $columns => $value){
        $updateDataString .= $columns . '=' . "'$value'";
    }

    $finalUpdateData = substr(trim($updateDataString),0, -1);
    $query = "UPDATE $table SET $finalUpdateData WHERE id = '$id'"; 
    $result = mysqli_query($conn , $query);
    return $result;

}

function getAll($tableName , $status = NULL){
    global $conn;

    $table = validate($tableName);
    $status = validate($status);

    if($status == 'status'){
        $query = "SELECT * FROM $table WHERE status = '0'";
    
    }else{
        $query = "SELECT * FROM $table";
    }

    return mysqli_query($conn , $query);
}

function getById($tableName , $id){
    global $conn;

    $table = validate($tableName);
    $id = validate($$id);

    $query = "SELECT * FROM $table WHERE id = '$id' LIMIT 1";
    $result = mysqli_query($conn , $query);

    if($result){
        
    if(mysqli_num_rows($result) == 1){

        $row = mysqli_fetch_assoc($result);
        
        $response = [
            'status' => 404,
            'data' => $row,
            'message' => 'Record Found' 
        ];

        return $response;

        }else{

        $response = [
            'status' => 404,
            'message' => 'No Data Found' 
        ];
        
        return $response;
    }
    
    }else{
        $response = [
            'status' => 500,
            'message' => 'Something Went Wrong' 
        ];

        return $response;
    }
}

// Delete Data from Database Using Id
function delete($tableName , $id){
    
    global $conn;

    $table = validate($tableName);
    $id = validate($id);

    $query = "DELETE FROM $table WHERE id = '$id' LIMIT 1";
    $result = mysqli_query($conn , $query);
    return $result;
}

?>