<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>mission_5-1</title>
</head>
<body>
    
<?php
    $dsn='データベース名';
	$user = 'ユーザー名';
	$password = 'パスワード';
	$pdo = new PDO($dsn, $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));
	
	$sql = "CREATE TABLE IF NOT EXISTS tbtest"
	." ("
	. "id INT AUTO_INCREMENT PRIMARY KEY,"
	. "name char(32),"
	. "comment TEXT,"
	. "date TEXT,"
	. "pass TEXT,"
	.");";
	$stmt = $pdo->query($sql);
	
	$name = $_POST["name"];
	$comment = $_POST["comment"]; 
    $date = date("Y/m/d/ H:i:s");
    $pass = $_POST["password"]; 

    $hidden = $_POST["hidden"];
    $delete = $_POST["delete"];
    $deletepass = $_POST["deletepass"];
    $edit = $_POST["edit"];
    $editpass = $_POST["editpass"];

    if(empty($str)&&empty($name)&&empty($delete)&&empty($edit)&&empty($hidden)&&empty($pass)&&empty($deletepass)&&empty($editpass)) {
        echo "コメントが入力されていません。";
    }
    else{
        if((isset($str)||isset($name))&&empty($delete)&&empty($edit)&&empty($deletepass)&&empty($editpass)){
            if(empty($hidden)){
                $sql = $pdo -> prepare("INSERT INTO tbtest (name, comment, date, pass) VALUES (:name, :comment, :date, :pass)");
	            $sql -> bindParam(':name', $name, PDO::PARAM_STR);
            	$sql -> bindParam(':comment', $comment, PDO::PARAM_STR);
	            $sql -> bindParam(':date', $date, PDO::PARAM_STR);		
                $sql -> bindParam(':pass', $pass, PDO::PARAM_STR);	   
	            $sql -> execute();
            }
        
            else{
                $id = $hidden; 
	            $sql = 'UPDATE tbtest SET name=:name,comment=:comment,date=:date,pass=:pass WHERE id=:id';
            	$stmt = $pdo->prepare($sql);
	            $stmt->bindParam(':name', $name, PDO::PARAM_STR);
	            $stmt->bindParam(':comment', $comment, PDO::PARAM_STR);
	            $stmt -> bindParam(':date', $date, PDO::PARAM_STR);		
                $stmt -> bindParam(':pass', $pass, PDO::PARAM_STR);	
	            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
	            $stmt->execute();
            }
        }
        //削除
        if(isset($delete)){
            $sql = 'SELECT * FROM tbtest';
	        $stmt = $pdo->query($sql);
	        $results = $stmt->fetchAll();
	        foreach ($results as $row){
        		if($row['id']==$delete){
        		    if($row['pass'] != $deletepass){
        		        echo "パスワードが違います。";
            		}
            		else{
            		    $id = $delete;
                        $sql = 'delete from tbtest where id=:id';
	                    $stmt = $pdo->prepare($sql);
	                    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
	                    $stmt->execute();
        		    }
        		}    
	        }
        }
    //編集
        if(isset($edit)){
            $sql = 'SELECT * FROM tbtest';
	        $stmt = $pdo->query($sql);
	        $results = $stmt->fetchAll();
	        foreach ($results as $row){
        		if($row['id']==$edit){
        		    if($row['pass'] != $editpass){
        		        echo "パスワードが違います。";
            		}
            		else{  
            		    $editnumber = $row['id'];
                        $editname = $row['name'];
                        $editcomment = $row['comment'];
                        $editpass = $row['pass'];
        		    }
        		}    
		    }
        }
        
    }
?>
    
    <form action="" method="post">
    <input type="text" name="name" placeholder="名前" value ="<?php echo $editname;?>"><br>
    <input type="text" name="comment" placeholder="コメント" value ="<?php echo $editcomment;?>">
    <input type="text" name="password" placeholder="パスワード" value ="<?php echo $editpass;?>">
    <input type="hidden" name="hidden" placeholder="隠すもの" value ="<?php echo $editnumber;?>">   
    <input type="submit" name="submit"><br>
    <input type="number" name="delete" placeholder="削除対象番号" style="width:100px">
    <input type="text" name="deletepass" placeholder="パスワード" style="width:100px">
    <input type="submit" name="submit" value="削除"><br>
    <input type="number" name="edit" placeholder="編集対象番号" style="width:100px">
    <input type="text" name="editpass" placeholder="パスワード" style="width:100px">
    <input type="submit" name="submit" value="編集">
    </form>
    
<?php    
    $sql = 'SELECT * FROM tbtest';
	$stmt = $pdo->query($sql);
	$results = $stmt->fetchAll();
	foreach ($results as $row){
		echo $row['id'].',';
		echo $row['name'].',';
		echo $row['comment'].',';
		echo $row['date'].'<br>';
	echo "<hr>";
	}
     
?>

</body>
</html>