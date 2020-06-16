@extends('layouts.app')
@section('content')
<main class = "container">
    <h1>訪問スケジュール管理</h1>
    <h2>週間スケジュール</h2>

    <form method = "POST">
{{ csrf_field() }}
        <div class="btn-group btn-group-toggle" data-toggle="buttons">
            <ul id="ozTabs">
@foreach( $week as $data )
                <li id="t{{ $loop->iteration-1 }}" data-num="{{ $loop->iteration-1 }}" class="ozTab @if($loop->iteration === 1)active @endif">{{ $data }}</li>
@endforeach
            </ul>
        </div>
        <div class = "tabBody">

@for( $i = 0; $i <= 6; $i++)<!--1週間ぶんのテーブル出力-->
            <table id="c{{ $i }}" class = "tabContent @if($i === 0)active @endif">
                <p id="p{{ $i }}" class = "weekly_array @if($i === 0)active @endif">{{ $weekly_array[$i] }}</p>
                <thead>
                    <tr>
                        <th></th>
                        <th>利用者</th>
                        <th>利用種別</th>
                        <th>ヘルパー</th>
                    </tr>
                </thead>
                <tbody>
    @foreach( $times as $time )
                    <tr>
                        <th scope="row">{{ $time }}</th>
                        <input type = "hidden" name = "time{{ $i }}{{ $loop->iteration-1 }}" value = "{{ $time }}">
                        <td>
                            <select name = "post_schedule_customer_name{{ $i }}{{ $loop->iteration-1 }}">
        @forelse( $customers as $customer )
                                <option value="{{ $customer->name }}">{{ $customer->name }}</option>
        @empty
                                <option>no customer</option>     
        @endforelse
                            </select>
                        </td>
                        <td>
                            <select name = "post_schedule_service_type{{ $i }}{{ $loop->iteration-1 }}">
        @forelse( $serviceTypes as $serviceType )
                                <option value = "{{ $serviceType->service_type }}">{{ $serviceType->service_type }}</option>
        @empty
                                <option>no service</option> 
        @endforelse    
                            </select>
                        </td>
                        <td>
                            <select name = "post_schedule_staff_name{{ $i }}{{ $loop->iteration-1 }}">
        @forelse( $staffs as $staff )
                                <option value = "{{ $staff->name }}">{{ $staff->name }}</option>
        @empty
                                <option>no user</option>           
        @endforelse
                            </select>
                        </td>
                    </tr>
    @endforeach
                </tbody>
            </table>
@endfor
        </div>
        <input type = "submit" name = "weekly_schedule" value = "予定を確定させる">
    </form>
    <a href = "#">月間スケジュールはこちらから</a>
    <h1>スタッフ管理</h1>
    <h1>利用者管理</h1>
    <h1>施設情報管理</h1>
    <p>ここは管理者ページです</p>
</main>

@endsection