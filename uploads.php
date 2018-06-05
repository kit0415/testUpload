<?php
/**
 * Created by PhpStorm.
 * User: Simon
 * Date: 28/5/2018
 * Time: 下午 4:20
 */
//$returnData =array(1,$target_file,$imageFileType,$target_dir);

function uploadFile($uploadOk,$target_file,$imageFileType,$target_dir){
    if ($uploadOk == 0) {
        echo "Sorry, your file was not uploaded.";
        return 0;
// if everything is ok, try to uploads file
    } else {
        echo "in here ".$target_dir;
        $status =move_uploaded_file(($_FILES["fileToUpload"]["tmp_name"]), $target_file);
        echo "status is: ".(string)$status;
        if ($status == 1) {
            echo "in anihter akise";
            if (checkFileType($imageFileType) == 1){
                chmod($target_file, 0777);
                $filename1 = basename($_FILES["fileToUpload"]["name"],".c");
                $filename2 = basename($_FILES["fileToUpload"]["name"]);
                $dataFile = array("uploads/testcase/input/C/cdata1.in","uploads/testcase/input/C/cdata2.in","uploads/testcase/input/C/cdata3.in");
                $commandList =array("gcc -o ".$target_dir.$filename1." ".$target_dir.$filename2,"./".$target_dir.$filename1."<".$dataFile[0],"./".$target_dir.$filename1."<".$dataFile[1],"./".$target_dir.$filename1."<".$dataFile[2]);
                //print_r($commandList);

                shell_exec($commandList[0]);
                $outputCommand = array("cat uploads/testcase/expected_outcome/C/testcase1.txt","cat uploads/testcase/expected_outcome/C/testcase2.txt","cat uploads/testcase/expected_outcome/C/testcase3.txt");
                $expected_outcome = array(shell_exec($outputCommand[0]),shell_exec($outputCommand[1]),shell_exec($outputCommand[2]));
                $user_outcome = array(shell_exec($commandList[1]),shell_exec($commandList[2]),shell_exec($commandList[3]));
                // sleep(2);
                $result = array_diff(array_map("trim",$expected_outcome),array_map("trim",$user_outcome));
                $testing = shell_exec("diff -b uploads/testcase/expected_outcome/C/testcase1.txt uploads/IS/ICT1001/1700210/Lab1/Task1/testResult.txt");
                echo "Using diff result is ".$testing;
                $score = round(100.0 - (count($result)*(100/3)),2);
                echo "<table>";
                echo "<tr><th>Expected Outcome:</th><th>Your Outcome:</th></tr>";
                echo "<tr><td>$expected_outcome[0]</td><td>$user_outcome[0]</td></tr>";
                echo "<tr><td>$expected_outcome[1]</td><td>$user_outcome[1]</td></tr>";
                echo "<tr><td>$expected_outcome[2]</td><td>$user_outcome[2]</td></tr>";
                echo "<tr><td>Score: </td><td>$score</td></tr>";
                echo "</table></br>";

                // sleep(5);

                echo "Filename is: ".$target_file."<br/>";
                echo "Basename is: ".$_FILES["fileToUpload"]["name"]."<br/>";
                echo "The file " . basename($_FILES["fileToUpload"]["name"]) . " has been uploaded.";
            }
            elseif (checkFileType($imageFileType)==2){
                chmod($target_file, 0777);
                $filename1 = basename($_FILES["fileToUpload"]["name"]);
                $dataFile = array("uploads/testcase/input/python/pythondata1.in","uploads/testcase/input/python/pythondata2.in","uploads/testcase/input/python/pythondata3.in");
                $commandList =array("python ".$filename1."<".$dataFile[0],"python ".$filename1."<".$dataFile[1],"python ".$filename1."<".$dataFile[2]);
                //print_r($commandList);
               
                $outputCommand = array("cat uploads/testcase/expected_outcome/python/pythoncase.txt","cat uploads/testcase/expected_outcome/python/pythoncase2.txt","cat uploads/testcase/expected_outcome/python/pythoncase3.txt");
                $expected_outcome = array(shell_exec($outputCommand[0]),shell_exec($outputCommand[1]),shell_exec($outputCommand[2]));
                $user_outcome = array(shell_exec($commandList[0]),shell_exec($commandList[1]),shell_exec($commandList[2]));
                // sleep(2);
                $result = array_diff(array_map("trim",$expected_outcome),array_map("trim",$user_outcome));
                $testing = shell_exec("diff -b uploads/IS/ICT1001/1700210/Lab1/Task1/testResult.txt /var/www/html/testUpload/uploads/testcase/expected_outcome/C/testcase1.txt");
                echo "Using diff result is ".$testing;
                $score = round(100.0 - (count($result)*(100/3)),2);
                echo "<table>";
                echo "<tr><th>Expected Outcome:</th><th>Your Outcome:</th></tr>";
                echo "<tr><td>$expected_outcome[0]</td><td>$user_outcome[0]</td></tr>";
                echo "<tr><td>$expected_outcome[1]</td><td>$user_outcome[1]</td></tr>";
                echo "<tr><td>$expected_outcome[2]</td><td>$user_outcome[2]</td></tr>";
                echo "<tr><td>Score: </td><td>$score</td></tr>";
                echo "</table></br>";

                // sleep(5);

                echo "Filename is: ".$target_file."<br/>";
                echo "Basename is: ".$_FILES["fileToUpload"]["name"]."<br/>";
                echo "The file " . basename($_FILES["fileToUpload"]["name"]) . " has been uploaded.";
            }


        }
        else{
            echo "File is not uploaded<br/>";
            return 0;
        }
    }
}

function checkFileExists($target_file){
    if (file_exists($target_file)) {
        echo "Sorry, file already exists.";
        return 0;
    }
    return 1;
}




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

function createDir(){
    if (isset($_POST["task"]) && isset($_POST["lab"]) &&!empty($_POST["task"]) && !empty($_POST["lab"])){
        $taskName = $_POST["task"];
        $labName = $_POST["lab"];
        shell_exec('mkdir -p uploads/IS/ICT1001/1700210/'.$labName.'/'.$taskName.'/');
        shell_exec('chmod -R 737 uploads/');
        $target_dir = "uploads/IS/ICT1001/1700210/".$labName."/".$taskName."/";
        $target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);

        $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
        $returnData =array(1,$target_file,$imageFileType,$target_dir);
        //print_r($returnData);
        return $returnData;
    }
    else{
        echo "Not set";
        $returnData =array(0);
        return $returnData;
    }
}
createDir();
uploadFile(createDir()[0],createDir()[1],createDir()[2],createDir()[3]);