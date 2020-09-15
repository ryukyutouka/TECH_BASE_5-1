<!DOCTYPE html>
<html lang="ja">
<head>
	<meta charset="UTF-8">
	<title>mission_5-1</title>
</head>
<body>
    投稿フォーム
    <form action="" method="post">
        <input type="text" name="postname" placeholder="名前">
        <input type="text" name="postcomment" placeholder="コメント">
        <input type="text" name="passpass" placeholder="パスワード">
        <input type="submit" name="submit">
    </form>
    <br>
    削除フォーム
    <form action="" method="post">
        <input type="number" name="number" placeholder="番号を指定">
        <input type="text" name="delpass" placeholder="パスワード">
        <input type="submit" name="submit02">
    </form>
    <br>
    編集フォーム
    <form action="" method="post">
        <input type="number" name="editnum" placeholder="番号を指定">
        <input type="text" name="editname" placeholder="名前">
        <input type="text" name="editcom" placeholder="コメント">
        <input type="text" name="editpass" placeholder="パスワード">
        <input type="submit" name="submit03">
    </form>
    <?php
        $dsn= 'データベース名';
        $user= 'ユーザー名';
        $password= 'パスワード';
        $pdo= new PDO($dsn, $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));

        $sql= "CREATE TABLE IF NOT EXISTS forumtest"
        . " ("
        . "id INT AUTO_INCREMENT PRIMARY KEY,"
        . "name char(32),"
        . "comment TEXT,"
        . "date DATE,"
        . "postpass TEXT"
        . ");";
        $stmt= $pdo->query($sql);

        if(!empty($_POST['submit'])){
            $sql= $pdo->prepare("INSERT INTO forumtest (name, comment, date, postpass) VALUES(:name, :comment, :date, :postpass)");
            $sql->bindParam(':name', $name, PDO::PARAM_STR);
            $sql->bindParam(':comment', $comment, PDO::PARAM_STR);
            $sql->bindParam(':date', $date, PDO::PARAM_STR);
            $sql->bindParam(':postpass', $passpass, PDO::PARAM_STR);
            $name= $_POST['postname'];
            $comment= $_POST['postcomment'];
            $date= date("Y/m/d");
            $passpass= $_POST['passpass'];
            $sql->execute();
        }

        if(!empty($_POST['submit02'])){
            $id= $_POST['number'];
            $delpass= $_POST['delpass'];
            $sql= 'SELECT * FROM forumtest';
            $stmt= $pdo->query($sql);
            $results= $stmt->fetchALL();
            foreach($results as $row){
                if($row['id'] == $id){
                    $getpass= $row['postpass'];
                }
            }
            if($delpass != $getpass){
                echo "パスワードが間違っています。<br>";
            }else{
                $sql= 'delete from forumtest WHERE id=:id';
                $stmt = $pdo->prepare($sql);
                $stmt->bindParam(':id', $id, PDO::PARAM_INT);
                $stmt->execute();
            }
        }

        if(!empty($_POST['submit03'])){
            $id= $_POST['editnum'];
            $editname= $_POST['editname'];
            $editcom= $_POST['editcom'];
            $editpass= $_POST['editpass'];
            $sql= 'SELECT * FROM forumtest';
            $stmt= $pdo->query($sql);
            $results= $stmt->fetchALL();
            foreach($results as $row){
                if($row['id'] == $id){
                    $getpass= $row['postpass'];
                }
            }
            if($editpass != $getpass){
                echo "パスワードが間違っています。<br>";
            }else{
                $sql = 'UPDATE forumtest SET name=:name,comment=:comment WHERE id=:id';
                $stmt = $pdo->prepare($sql);
                $stmt->bindParam(':name', $editname, PDO::PARAM_STR);
                $stmt->bindParam(':comment', $editcom, PDO::PARAM_STR);
                $stmt->bindParam(':id', $id, PDO::PARAM_INT);
                $stmt->execute();
            }
        }
        
        echo "<hr>";
        $sql = 'SELECT * FROM forumtest';
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