<head>
    <meta charset="UTF-8">
    {{--<meta http-equiv="Content-Security-Policy" content="default-src 'none'; connect-src 'self'; script-src 'unsafe-eval' https://d3js.org https://cdnjs.cloudflare.com https://cdn.ckeditor.com https://cdn.datatables.net https://oss.maxcdn.com https://code.jquery.com 'self' 'sha256-pe4U6kIWvrp1LYRPPKDjgvog99zgOja1SAPha5AO1sw=' 'sha256-BCCScT9C0ICxYJCMfzw5DVH+ibf/f88Gi4G5yPszRIA=' 'sha256-xugAxq776Km5ZNuMcNSDpkGLE9r1RrkWNSJDYt55hqM=' 'sha256-zE0ISxZF2+ZMpBlIs4cMyMeGBXpordNKFCZ9UnCA7aE=' 'sha256-8NmMqg3RfyNpM670z4R8QF3vjAFZ21M5OTVYWfuTBK4=' 'sha256-EHyOnMkEOB8pNSL3baDzuqKTbGusqfCCk0DtZlLVpKs=' 'sha256-kEk5QafW4yjqNHPYHSZ9zUUK3SaiOaXktDlHY8/vn2w=' 'nonce-4AEemasdGb0xJptoIGFP3Nd'; img-src * data:; style-src 'self' https://cdn.datatables.net https://fonts.googleapis.com https://code.jquery.com https://fonts.gstatic.com 'unsafe-inline'; frame-src *; font-src *">--}}
    <meta http-equiv="Content-Security-Policy" content="connect-src 'self' https://submissions.storage.mobicloud.asu.edu ; script-src 'unsafe-eval' https://d3js.org https://cdnjs.cloudflare.com https://cdn.ckeditor.com https://cdn.datatables.net https://oss.maxcdn.com https://code.jquery.com 'self' 'unsafe-inline' blob:; img-src * data:; style-src 'self' https://cdn.datatables.net https://fonts.googleapis.com https://code.jquery.com https://fonts.gstatic.com 'unsafe-inline'; frame-src *; font-src * data:;">
    <title> ThoTh Lab - @yield('htmlheader_title', 'Your title here') </title>
    <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link href="{{ mix('/css/all.css') }}" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.16/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/1.5.1/css/buttons.dataTables.min.css">

    <link rel="stylesheet"
          href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->

    <script src="https://code.jquery.com/jquery-3.4.1.js"></script>
    <script src="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>

    <script src="https://cdn.datatables.net/buttons/1.5.1/js/dataTables.buttons.min.js"></script>
    <script src="/vendor/datatables/buttons.server-side.js"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.0/Chart.bundle.min.js"></script>

    <script>
        //See https://laracasts.com/discuss/channels/vue/use-trans-in-vuejs
        window.trans = @php
            // copy all translations from /resources/lang/CURRENT_LOCALE/* to global JS variable
            $lang_files = File::files(resource_path() . '/lang/' . App::getLocale());
            $trans = [];
            foreach ($lang_files as $f) {
                $filename = pathinfo($f)['filename'];
                $trans[$filename] = trans($filename);
            }
            $trans['adminlte_lang_message'] = trans('adminlte_lang::message');
            echo json_encode($trans);
        @endphp
    </script>
    @stack('scripts')
    @stack('style')
</head>
