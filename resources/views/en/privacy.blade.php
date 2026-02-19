@extends('en.layouts.app')

@section('title', __('general.privacy_title'))

@section('content')
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            color: #1e3a8a;
            line-height: 1.6;
            background-color: #ffffff;
        }

        .header {
            background-color: #fff;
            padding: 15px 0;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .header-content {
            max-width: 1280px;
            margin: 0 auto;
            padding: 0 1.5rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .logo {
            font-size: 24px;
            font-weight: bold;
            color: #0066b2;
        }

        .search-bar {
            flex: 1;
            max-width: 500px;
            margin: 0 30px;
            display: flex;
        }

        .search-bar input {
            flex: 1;
            padding: 8px 15px;
            border: 1px solid #ddd;
            border-right: none;
            font-size: 14px;
        }

        .search-bar button {
            padding: 8px 20px;
            background-color: #0066b2;
            color: white;
            border: none;
            cursor: pointer;
        }

        .login-btn {
            background-color: #0066b2;
            color: white;
            padding: 8px 25px;
            border: none;
            cursor: pointer;
            font-size: 14px;
        }

        .nav {
            background-color: #fff;
            border-bottom: 1px solid #e0e0e0;
        }

        .nav-content {
            max-width: 1280px;
            margin: 0 auto;
            padding: 0 1.5rem;
            display: flex;
            gap: 30px;
        }

        .nav a {
            padding: 15px 0;
            text-decoration: none;
            color: #555;
            font-size: 14px;
            display: inline-block;
        }

        .nav a:hover {
            color: #0066b2;
        }

        .main-content {
            max-width: 1280px;
            margin: 0 auto;
            padding: 3rem 1.5rem;
        }

        .page-title {
            color: #1e3a8a;
            font-size: 2rem;
            font-weight: 600;
            margin-bottom: 0.5rem;
            text-align: left;
            letter-spacing: 0.025em;
        }

        @media (min-width: 768px) {
            .page-title {
                font-size: 2.25rem;
            }
        }

        .effective-date {
            color: #6b7280;
            font-size: 0.875rem;
            margin-bottom: 3rem;
            text-align: left;
        }

        .content-section {
            width: 100%;
            max-width: 1280px;
            margin: 0 auto;
        }

        .content-box {
            display: flex;
            flex-direction: column;
            gap: 2rem;
            padding: 1.5rem;
            border-radius: 0.75rem;
        }

        @media (min-width: 768px) {
            .content-box {
                gap: 3rem;
            }
        }

        article {
            flex: 1;
            color: #1e3a8a;
            line-height: 1.75;
            font-size: 1rem;
            text-align: left;
        }

        @media (min-width: 768px) {
            article {
                font-size: 1.125rem;
            }
        }

        article p {
            margin-bottom: 1.5rem;
        }

        article a {
            color: #0066b2;
            text-decoration: none;
        }

        article a:hover {
            text-decoration: underline;
        }

        .section-title {
            color: #1e3a8a;
            font-size: 1.25rem;
            margin-top: 2rem;
            margin-bottom: 1rem;
            font-weight: 600;
        }

        @media (min-width: 768px) {
            .section-title {
                font-size: 1.5rem;
            }
        }

        .section-subtitle {
            color: #1e3a8a;
            font-size: 1rem;
            margin-top: 1.5rem;
            margin-bottom: 0.75rem;
            font-weight: 600;
        }

        @media (min-width: 768px) {
            .section-subtitle {
                font-size: 1.125rem;
            }
        }

        .bullet-list {
            margin-left: 1.5rem;
            margin-bottom: 1.5rem;
            color: #1e3a8a;
        }

        .bullet-list li {
            margin-bottom: 0.75rem;
            line-height: 1.75;
        }

        .address-box {
            background-color: #f3f4f6;
            padding: 1.25rem;
            margin: 1.5rem 0;
            border-left: 4px solid #1e3a8a;
            border-radius: 0.25rem;
        }

        .address-box p {
            margin-bottom: 0.5rem;
            font-size: 0.875rem;
            color: #1e3a8a;
        }

        @media (min-width: 768px) {
            .address-box p {
                font-size: 1rem;
            }
        }

        .footer {
            background-color: #001f3f;
            color: white;
            padding: 2.5rem 0 1.25rem 0;
            margin-top: 4rem;
        }

        .footer-content {
            max-width: 1280px;
            margin: 0 auto;
            padding: 0 1.5rem;
            display: flex;
            flex-direction: column;
            gap: 2rem;
        }

        @media (min-width: 768px) {
            .footer-content {
                flex-direction: row;
                justify-content: space-between;
            }
        }

        .footer-left h3 {
            font-size: 1rem;
            margin-bottom: 1rem;
            font-weight: 600;
        }

        .footer-left p {
            font-size: 0.875rem;
            margin-bottom: 0.5rem;
            color: #ccc;
        }

        .footer-left a {
            color: #66b3ff;
            text-decoration: none;
        }

        .footer-left a:hover {
            text-decoration: underline;
        }

        .footer-right a {
            display: block;
            color: white;
            text-decoration: none;
            margin-bottom: 0.75rem;
            font-size: 0.875rem;
        }

        .footer-right a:hover {
            color: #66b3ff;
        }

        .footer-bottom {
            text-align: center;
            margin-top: 2rem;
            padding-top: 1.25rem;
            border-top: 1px solid #003366;
            color: #999;
            font-size: 0.75rem;
        }

        @media (min-width: 768px) {
            .footer-right {
                text-align: right;
            }
        }
    </style>
    </head>

    <body>


        <div class="main-content">
            <h1 class="page-title">{{ __('general.privacy_header') }}</h1>
            <p class="effective-date">{{ __('general.privacy_effective_date') }}</p>

            <section class="content-section">
                <div class="content-box">
                    <article>
                        <p>{!! __('general.privacy_p1') !!}</p>

                        <p>{{ __('general.privacy_p2') }}</p>

                        <h2 class="section-title">{{ __('general.privacy_types_of_data_collected') }}</h2>
                        <p>{{ __('general.privacy_p3') }}</p>

                        <p>{{ __('general.privacy_p4') }}</p>

                        <h2 class="section-title">{{ __('general.privacy_how_data_is_used') }}</h2>
                        <p class="section-subtitle">{{ __('general.privacy_collected_data_subtitle') }}</p>
                        <ul class="bullet-list">
                            <li>{{ __('general.privacy_use_li1') }}</li>
                            <li>{{ __('general.privacy_use_li2') }}</li>
                            <li>{{ __('general.privacy_use_li3') }}</li>
                            <li>{{ __('general.privacy_use_li4') }}</li>
                            <li>{{ __('general.privacy_use_li5') }}</li>
                        </ul>

                        <p>{{ __('general.privacy_p5') }}</p>

                        <h2 class="section-title">{{ __('general.privacy_how_data_is_shared') }}</h2>
                        <p class="section-subtitle">{{ __('general.privacy_share_subtitle') }}</p>
                        <ul class="bullet-list">
                            <li>{{ __('general.privacy_share_li1') }}</li>
                            <li>{{ __('general.privacy_share_li2') }}</li>
                            <li>{{ __('general.privacy_share_li3') }}</li>
                            <li>{{ __('general.privacy_share_li4') }}</li>
                            <li>{{ __('general.privacy_share_li5') }}</li>
                        </ul>

                        <h2 class="section-title">{{ __('general.privacy_do_not_track_title') }}</h2>
                        <p>{!! __('general.privacy_p6') !!}</p>

                        <h2 class="section-title">{{ __('general.privacy_changes_title') }}</h2>
                        <p>{{ __('general.privacy_p7') }}</p>

                        <h2 class="section-title">{{ __('general.privacy_access_title') }}</h2>
                        <p>{{ __('general.privacy_p8') }}</p>

                        <div class="address-box">
                            <p><strong>{{ __('general.privacy_address') }}</strong></p>
                        </div>

                        <p>{{ __('general.privacy_p9') }}</p>

                        <p><a href="#">{{ __('general.privacy_cookie_policy_link') }}</a><br>
                            <a href="#">{{ __('general.privacy_gdpr_link') }}</a>
                        </p>

                        <p>{!! __('general.privacy_p10') !!}</p>
                    </article>
                </div>
            </section>
        </div>


    </body>

    </html>

@endsection
