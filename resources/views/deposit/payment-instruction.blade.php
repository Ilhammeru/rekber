<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Title</title>

    <!--begin::Fonts-->
		<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700" />
    <!--end::Fonts-->
    <!--begin::Global Stylesheets Bundle(used by all pages)-->
    <link href="{{ asset('css/plugins.bundle.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('css/style.bundle.css') }}" rel="stylesheet" type="text/css" />
    <!--end::Global Stylesheets Bundle-->
	<!--begin::Page Vendor Stylesheets(used by this page)-->
	<link href="{{ asset('css/datatables.min.css') }}" rel="stylesheet" type="text/css" />
	{{-- <link href="{{ asset('css/select2.min.css') }}" rel="stylesheet" type="text/css" /> --}}

    {{-- font awesome kit --}}
    <script src="https://kit.fontawesome.com/51783b8c07.js" crossorigin="anonymous"></script>

	<!--end::Page Vendor Stylesheets-->
	@stack('styles')
    <link rel="stylesheet" href="//code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">

    <style>
        #timer {
            font-size: 18px;
            color: orange;
            font-weight: bolder;
        }
        .pi-body {
            padding: 0 350px;
        }
        .ui-accordion .ui-accordion-content {
            height: auto !important;
        }
    </style>

    <!-- Styles -->
    <link href="{{ mix('css/app.css') }}" rel="stylesheet">
</head>
<body id="kt_body" class="header-tablet-and-mobile-fixed aside-enabled">
    <!--begin::Main-->
		<!--begin::Root-->
		<div class="d-flex flex-column flex-root">
			<!--begin::Page-->
			<div class="page d-flex flex-row flex-column-fluid">
				<!--begin::Wrapper-->
				<div class="wrapper d-flex flex-column flex-row-fluid ps-0" id="kt_wrapper">
					@include('partials.header')

					<!--begin::Content-->
					<div class="content d-flex flex-column flex-column-fluid" id="kt_content">
						<!--begin::Post-->
						<div class="post d-flex flex-column-fluid" id="kt_post">
							<!--begin::Container-->
							<div id="kt_content_container" class="container-xxl">

                                <div class="pi-body d-flex align-items-center justify-content-center">
                                    <div class="p-5 bg-white">
                                        <h4 class="text-center">Selesaikan pembayaran dalam</h4>
                                        <div id="timer-container" class="text-center">
                                            <p id="timer"></p>
                                        </div>
                                        <div class="text-center">
                                            <p class="mb-1" style="font-size: 12px;">Batas akhir pembayaran</p>
                                            <p class="fw-bolder" style="font-size: 16px;">{{ $expire }}</p>
                                        </div>

                                        {{-- table --}}
                                        <table class="table" style="min-width: 400px;">
                                            <tbody>
                                                <tr>
                                                    <td class="ps-3 border-bottom border-start border-top">
                                                        {{ $third_party_response['data']['payment_name'] }}
                                                    </td>
                                                    <td class="pe-3 text-end border-end border-top border-bottom">
                                                        <img src="{{ $selected_channel['icon_url'] }}" style="width: 55px; height: auto;" alt="">
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="ps-3 text-start border-start border-bottom" style="vertical-align: middle;">
                                                        <p class="mb-0" style="font-size: 10px;">Virtual Account Number</p>
                                                        <p class="fw-bolder mb-0" id="VAText">{{ $third_party_response['data']['pay_code'] }}</p>
                                                    </td>
                                                    <td class="pe-3 text-end border-end border-bottom" style="vertical-align: middle;">
                                                        <p class="mb-0 text-success mb-0" style="font-size: 12px; cursor: pointer;" onclick="copyVa()">Salin <i class="text-success fa fa-copy"></i></p>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="ps-3 text-start border-start border-bottom" style="vertical-align: middle;">
                                                        <p class="mb-0" style="font-size: 10px;">Total Pembayaran</p>
                                                        <p class="fw-bolder mb-0">{{ number_format($third_party_response['data']['amount']) }}</p>
                                                    </td>
                                                    <td class="pe-3 text-end border-end" style="vertical-align: middle;">
                                                        <p class="mb-0 text-success mb-0" style="font-size: 12px; cursor: pointer;">Lihat Detail</p>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>

                                        <a class="w-100 btn btn-sm border border-success text-success bg-transparent text-center mt-4"
                                            href="{{ $urlStatus }}">
                                            Cek Status Pembayaran
                                        </a>

                                        <div class="text-start mt-5">
                                            <h5 class="fw-bold">Cara Pembayaran</h5>
                                        </div>

                                        {{-- collapse --}}
                                        <div id="accordion">
                                            @foreach ($instructions as $instruction)
                                                <h3>{{ $instruction['title'] }}</h3>
                                                <div>
                                                    @foreach ($instruction['steps'] as $key => $step)
                                                        <p>
                                                            {{ $key + 1 }} {!! $step !!}
                                                        </p>
                                                    @endforeach
                                                </div>
                                            @endforeach
                                        </div>

                                    </div>
                                </div>

							</div>
							<!--end::Container-->
						</div>
						<!--end::Post-->
					</div>
					<!--end::Content-->
				</div>
				<!--end::Wrapper-->
			</div>
			<!--end::Page-->
		</div>
		<!--end::Root-->
		<!--begin::Scrolltop-->
		<div id="kt_scrolltop" class="scrolltop" data-kt-scrolltop="true">
			<!--begin::Svg Icon | path: icons/duotune/arrows/arr066.svg-->
			<span class="svg-icon">
				<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
					<rect opacity="0.5" x="13" y="6" width="13" height="2" rx="1" transform="rotate(90 13 6)" fill="black" />
					<path d="M12.5657 8.56569L16.75 12.75C17.1642 13.1642 17.8358 13.1642 18.25 12.75C18.6642 12.3358 18.6642 11.6642 18.25 11.25L12.7071 5.70711C12.3166 5.31658 11.6834 5.31658 11.2929 5.70711L5.75 11.25C5.33579 11.6642 5.33579 12.3358 5.75 12.75C6.16421 13.1642 6.83579 13.1642 7.25 12.75L11.4343 8.56569C11.7467 8.25327 12.2533 8.25327 12.5657 8.56569Z" fill="black" />
				</svg>
			</span>
			<!--end::Svg Icon-->
		</div>
		<!--end::Scrolltop-->
	<!--end::Main-->

	@include('partials.notify')

    <script src="/js/lang.js"></script>

    <script src="{{ mix('js/app.js') }}"></script>

    <!--begin::Global Javascript Bundle(used by all pages)-->
    <script src="{{ asset('js/plugins.bundle.js') }}"></script>
    <script src="{{ asset('js/scripts.bundle.js') }}"></script>
    <script src="{{ asset('js/jquery.min.js') }}"></script>
    <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.js"></script>
    <!--end::Global Javascript Bundle-->
	<!--begin::Page Vendors Javascript(used by this page)-->
	<script src="{{ asset('js/datatables.min.js') }}"></script>
	<!--end::Page Vendors Javascript-->
	<script>
		var dtLanguage = {
			"sEmptyTable": "Tidak ada data yang tersedia pada tabel ini",
			"sProcessing": "Sedang memproses...",
			"sLengthMenu": "Tampilkan _MENU_ entri",
			"sZeroRecords": "Tidak ditemukan data yang sesuai",
			"sInfo": "Menampilkan _START_ sampai _END_ dari _TOTAL_ entri",
			"sInfoEmpty": "Menampilkan 0 sampai 0 dari 0 entri",
			"sInfoFiltered": "(disaring dari _MAX_ entri keseluruhan)",
			"sInfoPostFix": "",
			"sSearch": "",
			"sUrl": "",
			"oPaginate": {
				"sFirst": "<<",
				"sPrevious": "<",
				"sNext": ">",
				"sLast": ">>"
			}
		};

		$.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
	</script>
	@stack('scripts')
    <script src="{{ asset('plugins/custom/easytimer.js/dist/easytimer.min.js') }}"></script>
    <script>
        var timer = new easytimer.Timer();
        var dateTime = '{{ $expire_raw }}';
        let start = moment(dateTime);
        let now = moment();
        let diff = moment.duration(now.diff(start));
        let hours = diff.hours();
        let minute = diff.minutes();
        let second = diff.seconds();

        timer.start({
            precision: 'seconds',
            startValues: {
                seconds: second,
                minutes: minute,
                hours: hours,
            }
        });

        $('#timer-container #timer').html(timer.getTimeValues().toString());

        timer.addEventListener('secondsUpdated', function (e) {
            $('#timer-container #timer').html(timer.getTimeValues().toString());
        });

        timer.addEventListener('targetAchieved', function (e) {
            $('#timer-container #timer').html('KABOOM!!');
        });
        $( function() {
            $( "#accordion" ).accordion();
        } );

        function copyVa() {
            var text = $('#VAText');
            const $temp = $("<input>");
            $("body").append($temp);
            $temp.val(text.text()).select();
            document.execCommand("copy");
            $temp.remove();
            handleSuccess('VA has been copied');
        }
    </script>

    {{-- <!-- Scripts --> --}}

</body>
</html>
