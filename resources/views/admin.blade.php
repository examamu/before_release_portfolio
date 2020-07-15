@extends('layouts.app')
@section('content')
<main class = "container ">
    <div class = "col-md-8 col-md-offset-2">
    <h1>訪問スケジュール管理</h1>
        <h2>週間スケジュール</h2>
        <form method = "POST">
    {{ csrf_field() }}
            <div class="weekly_toggle">
                <ul id="weekTabs">
                    <li class="ozTab tab_date active_week">今週</li>
                    <li class="ozTab tab_date">来週</li>
                    <li class="ozTab tab_date">再来週</li>
                </ul>
            </div>

<!-- エラー表示 -->

<!-- エラー表示 -->


            <div class="date_toggle" data-toggle="buttons">
                <ul id="ozTabs">
<!--日〜土を表示-->
@foreach( $week as $data )
                    <li id="t{{ $loop->iteration-1 }}" data-num="{{ $loop->iteration-1 }}" class="ozTab @if($loop->iteration === 1)active @endif">{{ $data }}</li>       
@endforeach
                </ul>
            </div>




            <div class = "tabBody">

@for( $i = 0; $i < 7; $i++)<!--1週間ぶんのテーブル出力-->
                <table id="c{{ $i }}" class = "col-md-12 tabContent @if($i === 0)active @endif">
                    <thead>
                        <tr>
                            <th id="p{{ $i }}" class = "tbody weekly_array @if($i === 0)active @endif">{{ $weekly_array[$i] }}</th>
                            <th>利用者</th>
                            <th>利用種別</th>
                            <th>ヘルパー</th>
                        </tr>
                    </thead>
                    <tbody>
        <!--時間の表示-->
    @foreach( $times as $time )
                        <input type = "hidden" name = "time{{ $i }}{{ $loop->iteration-1 }}" value = "{{ $time }}">
                        <input type = "hidden" name = "schedule_id{{ $i }}{{ $loop->iteration-1 }}" value ="{{ $get_weekly_schedules[$weekly_array[$i]][$time]['schedule_id'] }}">
                        <tr>
                            <th class = "table_time" scope="row">{{ $time }}</th>
                            <td class = "table_customer_name">
                                <select name = "post_schedule_customer_id{{ $i }}{{ $loop->iteration-1 }}">
        @forelse( $customers as $customer )
                <!--利用者一覧-->
                @if($loop->first)              
                                    <option value = "no_customer"></option>
                @endif
                <!-- もし取得できていれば-->
                @if( $get_weekly_schedules[$weekly_array[$i]][$time]['customer_data']['id'] === $customer->customer->id)
                                    <option value="{{ $customer->customer->id }}" selected>{{ $customer->customer->name }}</option>
                @else
                                    <option value="{{ $customer->customer->id }}">{{ $customer->customer->name }}</option>
                @endisset
        @empty
                                    <option value = "no_customer">no customer</option>     
        @endforelse
                                </select>
                            </td>
                            
                            <td class = "table_service_type">
                                <select name = "post_schedule_service_type{{ $i }}{{ $loop->iteration-1 }}">
        @forelse( $serviceTypes as $serviceType )
            @if($loop->first)
                                    <option value = "no_service"></option> 
            @endif

            @if( $get_weekly_schedules[$weekly_array[$i]][$time]['service_type_data']['id'] === $serviceType->id)
                                    <option value = "{{ $serviceType->id }}" selected>{{ $serviceType->service_type }}</option>
            @else
                                    <option value = "{{ $serviceType->id }}">{{ $serviceType->service_type }}</option>
            @endif
        @empty
                                    <option value = "no_service">施設の提供サービスが選択されていません</option> 
        @endforelse    
                                </select>
                            </td>
                            <td class = "table_staff_name">
                                <select name = "post_schedule_staff_id{{ $i }}{{ $loop->iteration-1 }}">
        @forelse( $staffs as $staff )
            @if($loop->first)
                                    <option value = "no_staff"></option>
            @endif

            @if( $get_weekly_schedules[$weekly_array[$i]][$time]['user_data']['id'] === $staff->user->id)
                                    <option value = "{{ $staff->user->id }}" selected>{{ $staff->user->name }}</option>
            @else
                                    <option value = "{{ $staff->user->id }}">{{ $staff->user->name }}</option>
            @endif
        @empty
                                    <option value = "no_staff">no user</option>           
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
                    <th>利用者情報変更</th>
                </tr>
            </thead>
            <tbody>
@forelse( $customers as $customer)
                <tr>
                    <td>{{ $customer->customer->name }}</td>
                    <td>{{ $customer->customer->care_type }}{{ $customer->customer->care_level }}</td>
                    <td>{{ $customer->date_of_use }}</td>
                    <td>
                        <form method = "POST">
                            <input type = "submit" name = "customer_suspension_update" value = "利用→休止">
                        </form>
                    </td>
                    <td>
                        <form method = "POST">
                            <input type = "submit" name = "customer_data_update" value = "情報を変更する">
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
            <a href = "{{ url('/customer') }}" class = "btn-primary btn-block">利用者管理ページへ移動</a>
        <p>※1年間利用がない場合は利用者削除されますのでご注意ください</p>
    </div>
</main>

@endsection