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
                        <th>備考の有無</th>
                    </tr>
                </thead>
                <tbody>
                    <!--ループ-->
                    <tr>
                        <td>11:00</td>
                        <td>〇〇さん</td>
                        <td>備考欄を確認してください</td>
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
                        <th>備考の有無</th>
                    </tr>
                </thead>
                <tbody>
                    <!--ループ-->
                    <tr>
                        <td>11:00</td>
                        <td>〇〇さん</td>
                        <td>メモ</td>
                        <td>
                            <form method = "POST">
                                <input type = "submit" name = "" value = "備考を確認">
                            </form>
                        </td>
                    </tr>
                    <!--ループ-->
                </tbody>
            </table>
        </article>

        <nav class = "mypage_button">
            <p>
                <a href = "#">自分の予定を確認する</a>
            </p>
            <p>
                <a href = "#">他の職員の予定を確認する</a>
            </p>
            <p>
                <a href = "#">登録情報変更</a>
            </p>
        </nav>
    </main>
</body>
</html>