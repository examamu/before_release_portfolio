<?php

    $items = \DB::table('staff')->get();
?>

<!DOCTYPE html>
<html lang = "ja">
<head>
    <meta charset = "UTF-8">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <title>Visit Plan | 〇〇さんマイページ</title>
</head>
<body>
    <header>
        <div class = "logo">
        </div>

        <div class = "logout_button">
            <a href = "#">ログアウト</a>
        </div>
    </header>
    <main>
        <article class = "next_visit_info">
            <p>10:00〜</p>
            <h1>次は〇〇さん宅です。</h1>
            <p>ここに備考欄に記載した内容が入ります。</p>
        </article>
        <artile>
            <h2>その後の予定</h2>
            <table>
                <thead>
                    <tr>
                        <th>時間</th>
                        <th>名前</th>
                        <th>伝達事項</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <!--ループ-->
                    <tr>
                        <td>11:00</td>
                        <td>〇〇さん</td>
                        <td>伝達事項を確認してください</td>
                        <td>
                            <form method = "POST">
                                <input type = "submit" name = "" value = "備考を確認">
                            </form>
                        </td>
                    </tr>
                    <!--ループ-->
                </tbody>
            </table>
        </artile>
        
        <article>
            <h2>終了した予定</h2>
            <table>
                <thead>
                    <tr>
                        <th>時間</th>
                        <th>名前</th>
                        <th>伝達事項</th>
                    </tr>
                </thead>
                <tbody>
                    <!--ループ-->
                    <tr>
                        <td>11:00</td>
                        <td>〇〇さん</td>
                        <td>
                            <form method = "POST">
                                <input type = "submit" name = "" value = "伝達事項を記入する">
                            </form>
                        </td>
                    </tr>
                    <!--ループ-->
                </tbody>
            </table>
        </article>

        <nav class = "mypage_button">
            <form>
                <input type = "submit" name = "" value = "自分の予定を確認する">
            </form>
            <form>
                <input type = "submit" name = "" value = "他の職員の予定を確認する">
            </form>
            <form>
                <input type = "submit" name = "" value = "登録情報変更">
            </form>
        </nav>
    </main>
</body>
</html>