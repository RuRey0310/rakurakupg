<?php

// データベースの接続情報
define( 'DB_HOST', 'localhost');
define( 'DB_USER', 'boardman');
define( 'DB_PASS', 'brute-force16');
define( 'DB_NAME', 'board');

// タイムゾーン設定
date_default_timezone_set('Asia/Tokyo');

// 変数の初期化
$now_date = null;
$data = null;
$file_handle = null;
$split_data = null;
$message_array = array();
$error_message = array();
$clean = array();

session_start();

if( !empty($_POST['btn_submit']) ) {
	
	// 表示名の入力チェック
	if( empty($_POST['view_name']) ) {
		$error_message[] = '表示名を入力してください。';
	} else {
		$clean['view_name'] = htmlspecialchars( $_POST['view_name'], ENT_QUOTES);

		// セッションに表示名を保存
		$_SESSION['view_name'] = $clean['view_name'];
	}
	
	// メッセージの入力チェック
	if( empty($_POST['message']) ) {
		$error_message[] = 'メッセージを入力してください。';
	} else {
		$clean['message'] = htmlspecialchars( $_POST['message'], ENT_QUOTES);
	}

	if( empty($error_message) ) {
		
		// データベースに接続
		$mysqli = new mysqli( DB_HOST, DB_USER, DB_PASS, DB_NAME);

		// 接続エラーの確認
		if( $mysqli->connect_errno ) {
			$error_message[] = '書き込みに失敗しました。 エラー番号 '.$mysqli->connect_errno.' : '.$mysqli->connect_error;
		} else {

			// 文字コード設定
			$mysqli->set_charset('utf8');
			
			// 書き込み日時を取得
			$now_date = date("Y-m-d H:i:s");
			
			// データを登録するSQL作成
			$sql = "INSERT INTO message (view_name, message, post_date) VALUES ( '$clean[view_name]', '$clean[message]', '$now_date')";
			
			// データを登録
			$res = $mysqli->query($sql);
		
			if( $res ) {
				$_SESSION['success_message'] = 'メッセージを書き込みました。';
			} else {
				$error_message[] = '書き込みに失敗しました。';
			}
		
			// データベースの接続を閉じる
			$mysqli->close();
		}

		header('Location: board.php');
	}
}

// データベースに接続
$mysqli = new mysqli( DB_HOST, DB_USER, DB_PASS, DB_NAME);

// 接続エラーの確認
if( $mysqli->connect_errno ) {
	$error_message[] = 'データの読み込みに失敗しました。 エラー番号 '.$mysqli->connect_errno.' : '.$mysqli->connect_error;
} else {

	$sql = "SELECT view_name,message,post_date FROM message ORDER BY post_date DESC";
	$res = $mysqli->query($sql);

    if( $res ) {
		$message_array = $res->fetch_all(MYSQLI_ASSOC);
    }

    $mysqli->close();
}

?>
<!DOCTYPE html>
<html lang="ja">
<head>
<link rel="stylesheet" type="text/css" href="CSS/board.css">
<link rel="stylesheet" href="CSS/header.css" type="text/css" />
<title>掲示板</title>
<script src="JS/jquery-3.4.1.min.js"></script>
<script src="JS/rakurakupg.js"></script>
</head>
<div id="header"></div>
<meta charset="utf-8">
<title>掲示板</title>
</head>
<body>
<h1>掲示板</h1>
<?php if( empty($_POST['btn_submit']) && !empty($_SESSION['success_message']) ): ?>
    <p class="success_message"><?php echo $_SESSION['success_message']; ?></p>
    <?php unset($_SESSION['success_message']); ?>
<?php endif; ?>
<?php if( !empty($error_message) ): ?>
    <ul class="error_message">
		<?php foreach( $error_message as $value ): ?>
            <li>・<?php echo $value; ?></li>
		<?php endforeach; ?>
    </ul>
<?php endif; ?>
<form method="post">
	<div>
		<label for="view_name">表示名</label>
		<input id="view_name" type="text" name="view_name" value="<?php 
		if(isset($_SESSION['NAME'])){ 
			echo $_SESSION['NAME']; 
		} 
		else{
			echo "ゲスト";
		}
		?>" readonly>
	</div>
	<div>
		<label for="message">メッセージ</label>
		<textarea id="message" name="message"></textarea>
	</div>
	<input type="submit" name="btn_submit" value="書き込む">
</form>
<hr>
<section>
<?php if( !empty($message_array) ){ ?>
<?php foreach( $message_array as $value ){ ?>
<article>
    <div class="info">
        <h2><?php echo $value['view_name']; ?></h2>
        <time><?php echo date('Y年m月d日 H:i', strtotime($value['post_date'])); ?></time>
    </div>
    <p><?php echo nl2br($value['message']); ?></p>
</article>
<?php } ?>
<?php } ?>
</section>
</body>
</html>