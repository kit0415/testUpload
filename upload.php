<?php
if (isset($_POST["task"]) && isset($_POST["lab"]) &&!empty($_POST["task"]) && !empty($_POST["lab"])){
    $taskName = $_POST["task"];
    $labName = $_POST["lab"];
    shell_exec('mkdir -p uploads/IS/ICT1001/1700210/'.$labName.'/'.$taskName.'/');
    shell_exec('chmod -R 737 uploads/');
    $target_dir = "uploads/IS/ICT1001/1700210/".$labName."/".$taskName."/";
    $target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
    echo "target dir = ",$target_dir;
    echo "target file= ".$target_file;
    $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
    //$returnData =array(1,$target_file,$imageFileType,$target_dir);
    $uploadOk = 1;

}
else{
    echo "Not set";
    $uploadOk = 0;
}
// Check if image file is a actual image or fake image

// Check if file already exists
if (file_exists($target_file)) {
    echo "Sorry, file already exists.";
    $uploadOk = 0;
}

// Allow certain file formats
function checkFileType($imageFileType)
{

    if ($imageFileType == "c"||$imageFileType=="C"){
        echo "C file detected!";
        return 1;
    }
    if ($imageFileType =="py"||$imageFileType=="PY"){
        echo "Python file detected!";
        return 2;

    }
    else{
        echo "File is not allowed";
        return 0;

    }
}
// Check if $uploadOk is set to 0 by an error
if ($uploadOk == 0) {
    echo "Sorry, your file was not uploaded.";
// if everything is ok, try to upload file
} else {
    if (checkFileType($imageFileType) == 1){
        chmod($target_file, 0777);
        if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
            $filename1 = basename($_FILES["fileToUpload"]["name"], ".c");
            $filename2 = basename($_FILES["fileToUpload"]["name"]);
            $dataFile = array("/var/www/html/testUpload/uploads/testcase/input/C/cdata1.in", "/var/www/html/testUpload/uploads/testcase/input/C/cdata2.in", "/var/www/html/testUpload/uploads/testcase/input/C/cdata3.in");
            $commandList = array("gcc -o " . $target_dir . $filename1 . " " . $target_dir . $filename2, "./" . $target_dir . $filename1 . "<" . $dataFile[0], "./" . $target_dir . $filename1 . "<" . $dataFile[1], "./" . $target_dir . $filename1 . "<" . $dataFile[2]);
            //print_r($commandList);

            shell_exec($commandList[0]);
            $outputCommand = array("cat uploads/testcase/expected_outcome/C/testcase1.txt", "cat uploads/testcase/expected_outcome/C/testcase2.txt", "cat uploads/testcase/expected_outcome/C/testcase3.txt");
            $expected_outcome = array(shell_exec($outputCommand[0]), shell_exec($outputCommand[1]), shell_exec($outputCommand[2]));
            $user_outcome = array(shell_exec($commandList[1]), shell_exec($commandList[2]), shell_exec($commandList[3]));
            // sleep(2);
            $result = array_diff(array_map("trim", $expected_outcome), array_map("trim", $user_outcome));
            $testing = shell_exec("diff -b /var/www/html/testUpload/uploads/IS/ICT1001/1700210/Lab1/Task1/testResult.txt /var/www/html/testUpload/uploads/testcase/expected_outcome/C/testcase1.txt");
            echo "Using diff result is " . $testing;
            $score = round(100.0 - (count($result) * (100 / 3)), 2);
            echo "<table>";
            echo "<tr><th>Expected Outcome:</th><th>Your Outcome:</th></tr>";
            echo "<tr><td>$expected_outcome[0]</td><td>$user_outcome[0]</td></tr>";
            echo "<tr><td>$expected_outcome[1]</td><td>$user_outcome[1]</td></tr>";
            echo "<tr><td>$expected_outcome[2]</td><td>$user_outcome[2]</td></tr>";
            echo "<tr><td>Score: </td><td>$score</td></tr>";
            echo "</table></br>";

            // sleep(5);

            echo "Filename is: " . $target_file . "<br/>";
            echo "Basename is: " . $_FILES["fileToUpload"]["name"] . "<br/>";
            echo "The file " . basename($_FILES["fileToUpload"]["name"]) . " has been uploaded.";
        }
        else {
            echo "Sorry, there was an error uploading your file.";
        }
    }
//    if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
//        echo "The file ". basename( $_FILES["fileToUpload"]["name"]). " has been uploaded.";
//    } else {
//        echo "Sorry, there was an error uploading your file.";
//    }
}
