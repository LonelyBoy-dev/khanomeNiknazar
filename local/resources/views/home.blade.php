@section('script-link')
    <script src="{{asset(('assets/js/frotel/ostan.js'))}}"></script>
    <script src="{{asset('assets/js/frotel/city.js')}}"></script>
@endsection

@section('script')
    <script>
        loadOstan('ostan');

        $("#ostan").change(function () {
            var i = $(this).find('option:selected').val();
            ldMenu(i, 'city');
            $('.selectpicker').selectpicker('refresh');
        });

        function set_state_name() {
            var ostan_name = $('#ostan option:selected').text();
            var city_name = $('#city option:selected').text();
            $('input[name=city]').val(city_name);
            $('input[name=ostan]').val(ostan_name);
        }

        $('#ostan option').each(function (index) {

            var value_ostan = $(this).val();
            var state = '{{old('ostan_id')}}';
            if (value_ostan == state) {
                $(this).attr('selected', 'selected');
                ldMenu(value_ostan, 'city');

            }


        });

        $('.city option').each(function (index) {
            var city = '{{old('city_id')}}';
            var city_value = $(this).val();
            if (city_value == city) {
                $(this).attr('selected', 'selected');
                $('.selectpicker').selectpicker('refresh');
            }
        });


    </script>
@endsection

<input type="hidden" name="ostan" value="{{old('ostan')}}">
<input type="hidden" name="city" value="{{old('city')}}">

<div class="row">
    <div class="col-md-6 col-xs-12">
        <div class="form-group ">
            <label for="city" class="focus-label">انتخاب استان</label>
            <select id="ostan" class="form-control selectpicker" name="ostan_id">
                <option>-- انتخاب--</option>
            </select>
            @error('ostan')
            <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
            @enderror
        </div>

    </div>

    <div class="col-md-6 col-xs-12">
        <div class="form-group ">
            <label for="city" class="focus-label">انتخاب شهر</label>
            <select id="city" class="form-control selectpicker city" name="city_id" onchange="set_state_name()">
                <option>-- انتخاب--</option>
            </select>
            @error('city')
            <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
            @enderror
        </div>

    </div>
</div>
