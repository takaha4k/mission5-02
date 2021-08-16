<?php

 $dsn = 'mysql:dbname=tb230174db;host=localhost';
    $user = 'tb-230174';
    $password = '4n5wer2TAy';
    $pdo = new PDO($dsn, $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));

 $sql = "CREATE TABLE IF NOT EXISTS tbtest"
    ." ("
    . "id INT AUTO_INCREMENT PRIMARY KEY,"
    . "name char(32),"
    . "comment TEXT"
    .");";
    $stmt = $pdo->query($sql);

$sql ='SHOW TABLES';
    $result = $pdo -> query($sql);
    foreach ($result as $row){
        echo $row[0];
        echo '<br>';
    }
    echo "<hr>";
    
    $sql ='SHOW CREATE TABLE tbtest';
    $result = $pdo -> query($sql);
    foreach ($result as $row){
        echo $row[1];
    }
    echo "<hr>";
    
    $sql = $pdo -> prepare("INSERT INTO tbtest (name, comment) VALUES (:name, :comment)");
    $sql -> bindParam(':name', $name, PDO::PARAM_STR);
    $sql -> bindParam(':comment', $comment, PDO::PARAM_STR);
    $name = 'こんにちは！';
    $comment = '好きなアーティスト名を入れてね！'; //好きな名前、好きな言葉は自分で決めること
    $sql -> execute();
     
     $sql = 'SELECT * FROM tbtest';
    $stmt = $pdo->query($sql);
    $results = $stmt->fetchAll();
    foreach ($results as $row){
        //$rowの中にはテーブルのカラム名が入る
        echo $row['id'].',';
        echo $row['name'].',';
        echo $row['comment'].'<br>';
    echo "<hr>";
    }
    
    $id = 2;
    $sql = 'delete from tbtest where id=:id';
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    
     $sql = 'DROP TABLE tbtest';
        $stmt = $pdo->query($sql);
     
            $fname = "mission_5-1.txt";
            if((isset($_POST ["name"]) && isset($_POST ["str"]) && isset($_POST["password"]))  || (isset($_POST ["delete"]) && isset($_POST["deletepass"])) || (isset($_POST ["hensyuu"]) && isset($_POST ["editpass"]))){
                $name = $_POST["name"]; //名前
                $string = $_POST["str"]; //コメント
                $pass = $_POST["password"];
                $deleteNo = $_POST["deleteNo"]; //削除番号
                $editNo = $_POST["editNo"]; //編集番号指定用フォーム
                $editpass = $_POST["editpass"]; //編集パス
                $fp = fopen($fname , "a");
                
                //投稿
                if(!empty($name) && !empty($string) && !empty($pass) && empty($_POST["himitu"])){
                //$nameと$stringが入ってて秘密とパスが空の時
                if(file_exists($fname)){
                    echo "書き込みありがとう！<br><br>";
                    $date = date("Y/m/d H:i:s");
                    $lines = file($fname,FILE_IGNORE_NEW_LINES);//新しい行を無視
                    $num = 1;
                    foreach($lines as $li){
                        $str = explode("<>",$li);
                        if($li != ""){
                            $num = $str[0] + 1;
                        }
                    }
                $str = $num."<>".$name."<>".$string."<>".$date. "<>" . $pass . "<>" . PHP_EOL;
                fwrite($fp,$str);
                fclose($fp);
            }
        }
        elseif(!empty($name) && !empty($string) && !empty($pass) && !empty($_POST["himitu"])){
            if(file_exists($fname)){
        $lines = file($fname,FILE_IGNORE_NEW_LINES);
        $fp = fopen($fname,"w") ;
        fclose ($fp);
        $fp = fopen($fname,"a");
        foreach($lines as $li){
            $str = explode("<>",$li);
            if($_POST["himitu"] == $str[0]){
                fwrite($fp , $str[0] . "<>" . $name ."<>" . $string . "<>" . $str[3] . "<>" . $pass . "<>" . PHP_EOL);
            }else{
                fwrite($fp , $str[0] . "<>" . $str[1] . "<>" . $str[2] . "<>" . $str[3] . "<>" . $str[4] . "<>" . PHP_EOL);
                
            }
        }
        fclose($fp);
            }
        }
                //削除
                if(!empty($deleteNo) && !empty($_POST["deletepass"])){
            $deletepass = $_POST["deletepass"]; //削除パス
            $lines = file($fname,FILE_IGNORE_NEW_LINES);
            $fp = fopen($fname,"w" ) ;
            fclose ($fp);
        $fp = fopen($fname,"a");
            foreach($lines as $li){
                        $str = explode("<>",$li);
                        if($str[0] == $deleteNo && $deletepass == $str[4]){
                            
                        }else{
                        fwrite($fp , $str[0] . "<>" . $str[1] . "<>" . $str[2] . "<>" . $str[3] . "<>" . $str[4] . "<>". PHP_EOL);                            
                        }
                        
            }
           fclose($fp);
                }
                
                //編集
                if(!empty($editNo) && !empty($editpass)){
                    if(file_exists($fname)){
                    $lines = file($fname,FILE_IGNORE_NEW_LINES);//新しい行を無視
                    foreach($lines as $li){
                        $str = explode("<>",$li);
                        if($editNo == $str[0] && $editpass == $str[4]){
                            $hensyuuNo = $str[0];
                            $editname = $str[1];
                            $editstr = $str[2];
                        }
                    }
                    }
                }
                }
                
        ?>
<!DOCTYPE html>
<html lang="ja">
    <meta charest="UTF-8">
    <title>mission_5-1.php</title>
    <head>
        <form action="" method="post">
            <input type="text" name="name" value="<?php if(isset($editname)){echo $editname ;}?>" placeholder="名前"> <br>
            <input type="text" name="str" value ="<?php if(isset($editstr)){echo $editstr;}?>" placeholder="好きなアーティスト">
            <input type="password" name="password" placeholder="パスワード">
            <input type="submit" name="submit" value="送信"> <br><br>
            <input type="text" name="deleteNo" placeholder="削除対処番号"> <br>
            <input type="password" name="deletepass" placeholder="削除パスワード">
            <input type="submit" name="delete" value="削除"> <br><br>
            <input type="text" name="editNo" placeholder="編集番号指定用フォーム">
            <input type="hidden" name="himitu" value="<?php if(isset($hensyuuNo)){echo $hensyuuNo;}else{echo 0;}?>"> <br>
            <input type="password" name="editpass" placeholder="編集パスワード">
            <input type="submit" name="hensyuu" value="編集"><br><br>
            </form>
            
        <?php
            echo "[投稿一覧]<br>";
            $lines = file($fname,FILE_IGNORE_NEW_LINES);//新しい行を無視
                    foreach($lines as $li){
                        $str = explode("<>",$li);
                        echo $str[0]." ".$str[1]." ".$str[2]." ".$str[3]."<br>";
                    }
            ?>
    </head>
</html>