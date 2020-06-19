@extends('layouts.app')
@section('content')
<main class = "container ">
    <div class = "col-md-8 col-md-offset-2">
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
            <div class = "tabBody ">

    @for( $i = 0; $i <= 6; $i++)<!--1週間ぶんのテーブル出力-->
                <table id="c{{ $i }}" class = "tabContent @if($i === 0)active @endif">
                    <thead>
                        <tr>
                            <th id="p{{ $i }}" class = "tbody weekly_array @if($i === 0)active @endif">{{ $weekly_array[$i] }}</th>
                            <th>利用者</th>
                            <th>利用種別</th>
                            <th>ヘルパー</th>
                        </tr>
                    </thead>
                    <tbody>
        @foreach( $times as $time )
                        <tr>
                            <th class = "table_time" scope="row">{{ $time }}</th>
                            <input type = "hidden" name = "time{{ $i }}{{ $loop->iteration-1 }}" value = "{{ $time }}">
                            <td class = "table_customer_name">
                                <select name = "post_schedule_customer_name{{ $i }}{{ $loop->iteration-1 }}">
            @forelse( $customers as $customer )
            @if($loop->first)
                                    <option value = "no_customer"></option>
                @endif
                                    <option value="{{ $customer->name }}">{{ $customer->name }}</option>
            @empty
                                    <option value = "no_customer">no customer</option>     
            @endforelse
                                </select>
                            </td>
                            <td class = "table_service_type">
                                <select name = "post_schedule_service_type{{ $i }}{{ $loop->iteration-1 }}">
            @forelse( $serviceTypes as $serviceType )
                                    <option value = "{{ $serviceType->service_type }}">{{ $serviceType->service_type }}</option>
            @empty
                                    <option>no service</option> 
            @endforelse    
                                </select>
                            </td>
                            <td class = "table_staff_name">
                                <select name = "post_schedule_staff_name{{ $i }}{{ $loop->iteration-1 }}">
            @forelse( $staffs as $staff )
                @if($loop->first)
                                    <option value = "no_user"></option>
                @endif
                                    <option value = "{{ $staff->user->name }}">{{ $staff->user->name }}</option>
            @empty
                                    <option value = "no_user">no user</option>           
            @endforelse
                                </select>
                            </td>
                        </tr>
        @endforeach
                    </tbody>
                </table>
    @endfor
            </div>
            <input type = "submit" name = "weekly_schedule" value = "予定を確定させる" class = "btn-primary btn-block">
        </form>
        <a href = "#">月間スケジュールはこちらから</a>


        <h1>施設情報管理</h1>
        <form method = "POST">
            <table class = "table">
                <thead>
                    <tr>
                        <th>施設名</th>
                        <th>提供サービス</th>
                        <th>サービス提供時間</th>
                        <th>定休日</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><input type = "text" value = "{{ $facility_data->name }}"></td>
                        <td><input type = "text" value = "訪問介護"></td>
                        <td>{{ $facility_data->opening_hours }}から{{ $facility_data->closing_hours }}まで</td>
                        <td>日</td>
                    </tr>
                </tbody>
            </table>
            <input type = "submit" name = "weekly_schedule" value = "施設情報を変更する" class = "btn-primary btn-block">
        </form>


        <h1>スタッフ管理</h1>
        <table class = "table">
            <thead>
                <th>スタッフ名</th>
                <th>役職</th>
                <th></th>
            </thead>
            <tbody>
                <td>testuser</td>
                <td>管理者</td>
                <td>
                    <form method = "POST">
                        <input type = "submit" name = "staff_edit" value = "情報を変更する">
                    </form>
                </td>
            </tbody>
        </table>
        
        <h1>利用者管理</h1>
        <table class = "table">
            <thead>
                <tr>
                    <th>利用者名</th>
                    <th>要介護度</th>
                    <th>利用曜日</th>
                    <th>利用休止</th>
                </tr>
            </thead>
            <tbody>
@forelse( $customers as $customer)
                <tr>
                    <td>{{ $customer->name }}</td>
                    <td>{{$customer->care_type}}{{ $customer->care_level }}</td>
                    <td>日月火水木金土</td>
                    <td>
                        <form method = "POST">
                            <input type = "submit" name = "suspension_change" value = "利用→休止">
                        </form>
                    </td>
                </tr>
@empty
<tr>
    <td>利用者がいません</td>
</tr>
@endforelse
            </tbody>
        </table>
        <p>※1年間利用がない場合は利用者削除されますのでご注意ください</p>
    </div>
</main>

@endsection