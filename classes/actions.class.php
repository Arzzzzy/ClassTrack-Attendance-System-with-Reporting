<?php
class Actions {
    private $conn;

    // Constructor to establish database connection
    function __construct() {
        require_once(realpath(__DIR__.'/../connection.php'));
        $this->conn = $conn;
    }
    
    // Function to save class details
    public function save_class() {
        // Sanitize input data
        foreach($_POST as $k => $v) {
            if(!is_array($_POST[$k]) && !is_numeric($_POST[$k]) && !empty($_POST[$k])) {
                $_POST[$k] = addslashes(htmlspecialchars($v));
            }
        }
        extract($_POST);

        // Check if it's an update or a new entry
        if(!empty($id)) {
            $check = $this->conn->query("SELECT id FROM `class_tbl` where `name` = '{$name}' and `id` != '{$id}' ");
            $sql = "UPDATE `class_tbl` set `name` = '{$name}' where `id` = '{$id}'";
        } else {
            $check = $this->conn->query("SELECT id FROM `class_tbl` where `name` = '{$name}' ");
            $sql = "INSERT INTO `class_tbl` (`name`) VALUES ('{$name}')";
        }

        // Check if class name already exists
        if($check->num_rows > 0) {
            return ['status' => 'error', 'msg' => 'Class Name Already Exists!'];
        } else {
            $qry = $this->conn->query($sql);
            if($qry) {
                if(empty($id)) {
                    $_SESSION['flashdata'] = [ 'type' => 'success', 'msg' => "New Class has been added successfully!" ];
                } else {
                    $_SESSION['flashdata'] = [ 'type' => 'success', 'msg' => "Class Data has been updated successfully!" ];
                }
                return [ 'status' => 'success'];
            } else {
                if(empty($id)) {
                    return ['status' => 'error', 'msg' => 'An error occurred while saving the New Class!'];
                } else {
                    return ['status' => 'error', 'msg' => 'An error occurred while updating the Class Data!'];
                }
            }
        }
    }

    // Function to delete a class
    public function delete_class() {
        extract($_POST);
        $delete = $this->conn->query("DELETE FROM `class_tbl` where `id` = '{$id}'");
        if($delete) {
            $_SESSION['flashdata'] = [ 'type' => 'success', 'msg' => "Class has been deleted successfully!" ];
            return [ "status" => "success" ];
        } else {
            $_SESSION['flashdata'] = [ 'type' => 'danger', 'msg' => "Class has failed to be deleted due to unknown reason!" ];
            return [ "status" => "error", "Class has failed to be deleted!" ];
        }
    }

    // Function to list all classes
    public function list_class() {
        $sql = "SELECT * FROM `class_tbl` ORDER BY `name` ASC";
        $qry = $this->conn->query($sql);
        return $qry->fetch_all(MYSQLI_ASSOC);
    }

    // Function to get details of a specific class
    public function get_class($id = "") {
        $sql = "SELECT * FROM `class_tbl` WHERE `id` = '{$id}'";
        $qry = $this->conn->query($sql);
        return $qry->fetch_assoc();
    }
    
    // Function to save student details
    public function save_student() {
        // Sanitize input data
        foreach($_POST as $k => $v) {
            if(!is_array($_POST[$k]) && !is_numeric($_POST[$k]) && !empty($_POST[$k])) {
                $_POST[$k] = addslashes(htmlspecialchars($v));
            }
        }
        extract($_POST);

        // Check if it's an update or a new entry
        if(!empty($id)) {
            $check = $this->conn->query("SELECT id FROM `students_tbl` where `name` = '{$name}' and `class_id` = '{$class_id}' and `id` != '{$id}' ");
            $sql = "UPDATE `students_tbl` set `name` = '{$name}', `class_id` = '{$class_id}' where `id` = '{$id}'";
        } else {
            $check = $this->conn->query("SELECT id FROM `students_tbl` where `name` = '{$name}' and `class_id` = '{$class_id}' ");
            $sql = "INSERT INTO `students_tbl` (`name`, `class_id`) VALUES ('{$name}', '{$class_id}')";
        }

        // Check if student name already exists in the class
        if($check->num_rows > 0) {
            return ['status' => 'error', 'msg' => 'Student Name Already Exists!'];
        } else {
            $qry = $this->conn->query($sql);
            if($qry) {
                if(empty($id)) {
                    $_SESSION['flashdata'] = [ 'type' => 'success', 'msg' => "New Student has been added successfully!" ];
                } else {
                    $_SESSION['flashdata'] = [ 'type' => 'success', 'msg' => "Student Data has been updated successfully!" ];
                }
                return [ 'status' => 'success'];
            } else {
                if(empty($id)) {
                    return ['status' => 'error', 'msg' => 'An error occurred while saving the New Student!'];
                } else {
                    return ['status' => 'error', 'msg' => 'An error occurred while updating the Student Data!'];
                }
            }
        }
    }

    // Function to delete a student
    public function delete_student() {
        extract($_POST);
        $delete = $this->conn->query("DELETE FROM `students_tbl` where `id` = '{$id}'");
        if($delete) {
            $_SESSION['flashdata'] = [ 'type' => 'success', 'msg' => "Student has been deleted successfully!" ];
            return [ "status" => "success" ];
        } else {
            $_SESSION['flashdata'] = [ 'type' => 'danger', 'msg' => "Student has failed to be deleted due to unknown reason!" ];
            return [ "status" => "error", "Student has failed to be deleted!" ];
        }
    }

    // Function to list all students with their class names
    public function list_student() {
        $sql = "SELECT `students_tbl`.*, `class_tbl`.`name` as `class` FROM `students_tbl` INNER JOIN `class_tbl` ON `students_tbl`.`class_id` = `class_tbl`.`id` ORDER BY `students_tbl`.`name` ASC";
        $qry = $this->conn->query($sql);
        return $qry->fetch_all(MYSQLI_ASSOC);
    }

    // Function to get details of a specific student along with class name
    public function get_student($id = "") {
        $sql = "SELECT `students_tbl`.*, `class_tbl`.`name` as `class` FROM `students_tbl` INNER JOIN `class_tbl` ON `students_tbl`.`class_id` = `class_tbl`.`id` WHERE `students_tbl`.`id` = '{$id}'";
        $qry = $this->conn->query($sql);
        return $qry->fetch_assoc();
    }

    // Function to get student attendance for a specific class and date
    public function attendanceStudents($class_id = "", $class_date = "") {
        if(empty($class_id) || empty($class_date)) {
            return [];
        }
        $sql = "SELECT `students_tbl`.*, COALESCE((SELECT `status` FROM `attendance_tbl` WHERE `student_id` = `students_tbl`.id AND `class_date` = '{$class_date}' ), 0) as `status` FROM `students_tbl` WHERE `class_id` = '{$class_id}' ORDER BY `name` ASC";
        $qry = $this->conn->query($sql);
        return $qry->fetch_all(MYSQLI_ASSOC);
    }
    
    // Function to get student attendance for a specific class and month
    public function attendanceStudentsMonthly($class_id = "", $class_month = "") {
        if(empty($class_id) || empty($class_month)) {
            return [];
        }
        $sql = "SELECT `students_tbl`.* FROM `students_tbl` WHERE `class_id` = '{$class_id}' ORDER BY `name` ASC";
        $qry = $this->conn->query($sql);
        $result = $qry->fetch_all(MYSQLI_ASSOC);

        // Loop through each student to get their attendance
        foreach($result as $k => $row) {
            $att_sql = "SELECT `status`, `class_date` FROM `attendance_tbl` WHERE `student_id` = '{$row['id']}' ";
            $att_qry = $this->conn->query($att_sql);
            foreach($att_qry as $att_row) {
                $result[$k]['attendance'][$att_row['class_date']] = $att_row['status'];
            }
        }
        return $result;
    }

    // Function to save attendance for students
    public function save_attendance() {
        extract($_POST);
        $sql_values = "";
        $errors = "";

        // Loop through each student to save their attendance status
        foreach($student_id as $k => $sid) {
            $stat = $status[$k] ?? 3;

            // Update or insert attendance record
            $check = $this->conn->query("SELECT id FROM `attendance_tbl` WHERE `student_id` = '{$sid}' AND `class_date` = '{$class_date}' ");
            if($check->num_rows > 0) {
                $sql = "UPDATE `attendance_tbl` SET `status` = '{$stat}' WHERE `student_id` = '{$sid}' AND `class_date` = '{$class_date}' ";
            } else {
                $sql = "INSERT INTO `attendance_tbl` (`student_id`, `class_date`, `status`) VALUES ('{$sid}', '{$class_date}', '{$stat}')";
            }
            $qry = $this->conn->query($sql);
            if(!$qry) {
                $errors .= "Attendance failed to save for student ID: {$sid}\n";
            }
        }

        // Check for errors
        if(empty($errors)) {
            return [ 'status' => 'success' ];
        } else {
            return [ 'status' => 'error', 'msg' => $errors ];
        }
    }
}
?>
