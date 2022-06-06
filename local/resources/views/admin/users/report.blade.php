<table>
    <tbody>
    <tr>
        <td>نام و نام خانوادگی</td>
        <td>شماره موبایل</td>
        <td>ایمیل</td>
        <td>تاریخ ثبت نام</td>

    </tr>
    @foreach($users as $user)

        <tr style="vertical-align: middle">
            <td>{{$user->name.' '.$user->family}}</td>
            <td>{{$user->mobile}}</td>
            <td>{{$user->email}}</td>
            <td>{{Verta($user->created_at)}}</td>
        </tr>
    @endforeach
    </tbody>
</table>
