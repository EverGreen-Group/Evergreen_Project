<?php
    
    include 'db.php';

    // Check if the form is submitted   
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        
    // Retrieve form data
    $supplier_id = isset($_POST['supplier_id']) ? mysqli_real_escape_string($con, $_POST['supplier_id']) : '';
    $total_amount = isset($_POST['total_amount']) ? mysqli_real_escape_string($con, $_POST['total_amount']) : '';
    $address = isset($_POST['address']) ? mysqli_real_escape_string($con, $_POST['address']) : '';
    $email = isset($_POST['email']) ? mysqli_real_escape_string($con, $_POST['email']) : '';
    $phone_number = isset($_POST['phone_number']) ? mysqli_real_escape_string($con, $_POST['phone_number']) : '';
    

    if (!empty($supplier_id) && !empty($total_amount)  && !empty($address) && !empty($email) && !empty($phone_number)) {
        
        $query = "INSERT INTO fertilizer_requests (supplier_id, total_amount, address, email, phone_number ) 
                  VALUES ('$supplier_id', '$total_amount', '$address', '$email', '$phone_number')";

        // Execute query
        if (mysqli_query($con, $query)) {
            echo "Submitted successfully";
            header("refresh:2,url=../views/pages/FertilizerRequest.php");
        } else {
            echo "Error: " . mysqli_error($con);
        }
    } else {
        echo "Error: Could not submit";
    }
    

    mysqli_close($con);
}
?>
