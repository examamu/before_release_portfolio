@extends('layouts.app')
@section('content')
@foreach( $err_msg as $message)
{{ $message }}
@endforeach
<main class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <h1>ユーザー登録情報変更</h1>
            <form method = "POST">
                {{ csrf_field() }}
                <input type = "hidden" name = "user_id" value = "{{ $user_id }}">
                <table>
                    <tr>
                        <td>
                            <label>
                                <p>ユーザー名</p>
                                <p><input type = "text" name = "update_name" value = "{{ $user_name }}"></p>
                            </label>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <label>
                                <p>登録Emailアドレス</p>
                                <p><input type = "text" name = "update_email" value = "{{ $email }}"></p>
                            </label>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <label>
                                <p>施設名：{{ $facility_name }} </p>
                                <input type = "text" name = "update_facility"></p>
                            </label>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <input type = "submit" name = "update_user_data" value = "変更内容を保存する">
                        </td>
                    </tr>
            </table>
            </form>
        </div>
  
    </div>
</main>

@endsection
