

@section('body')

<body class="page-your-shopping-cart template-cart">
@stop


@section('content')
<div id="banner-marquee" class="marqueeWrapper">
                          <div class="dyncontent">
                             <div class="triangleBorder"></div>
                             <div class="triangleAngle"></div>
                          </div>
                          <ul id="marquee">
                             <li>
                                <div class="dyncontent">
                                   <div class="triangleBorder"></div>
                                   <div class="triangleAngle"></div>
                                   <!-- HTML Content Wrapper -->
                                   
                                   <!-- /HTML Content Wrapper -->
                                </div>
                             </li>
                          </ul>
              </div>
@stop
<!-- part 1 -->

               
               
               <link rel="stylesheet" type="text/css" href="{{ URL::asset( 'assets/vitheme/plugins/css-image/css/marquee.css') }}" charset="utf-8"/>


@section('pageJS')
<script type="text/javascript" src="{{ URL::asset( 'assets/vitheme/plugins/css-image/js/slider-img.js') }}"></script>
               

<script type="text/javascript">
  $(document).ready(function() {
      var t = {
        tabInterval: 5e3,
        imgroot: "https://cdn.staticsfly.com/i/home/member/",
        tabs: [{
            id: "tab1",
            tabHeader: "GRADUATION",
            tabdescription: "Rep your colors"
        }, {
            id: "tab2",
            tabHeader: "YEARBOOKS",
            tabdescription: "Best in class"
        }, {
            id: "tab3",
            tabHeader: "GIFTS FOR MOM",
            tabdescription: "Custom jewelry + more"
        }, {
            id: "tab4",
            tabHeader: "SHUTTERFLY PHOTOS",
            tabdescription: "Meet the NEW SHUTTERFLY"
        }],
        globalLayers: [{
            src: "P29019_sale_promo.png",
            top: 288,
            left: 0,
            height: 35,
            width: 188,
            href: "/special-offers",
            id: "globalLayer1"
        }],
        tabsMarquee: {
            tab1: [{
                src: "P41244_homepage_panel1_cs_MARQ_0403.jpg",
                layerImages: [{
                    top: 460,
                    left: 0,
                    height: 903,
                    width: 980,
                    href: "/cards-stationery/graduation-cards-stationery",
                    id: "t1"
                }, {
                    top: 0,
                    left: 216,
                    height: 460,
                    width: 535,
                    href: "/cards-stationery/graduation-announcements/striped-corners-graduation-announcement-5x7-flat",
                    id: "t1"
                }, {
                    top: 356,
                    left: 751,
                    height: 80,
                    width: 220,
                    href: "/cards-stationery/announcements/custom+color--graduation-announcements",
                    id: "t1"
                }]
            }],
            tab2: [{
                src: "P41244_homepage_panel1_pb_MARQ_0403.jpg",
                layerImages: [{
                    top: 0,
                    left: 188,
                    height: 405,
                    width: 792,
                    href: "/photo-books",
                    id: "t2"
                }, {
                    top: 405,
                    left: 188,
                    height: 55,
                    width: 496,
                    href: "/photo-books",
                    id: "t2"
                }, {
                    top: 460,
                    left: 0,
                    height: 90,
                    width: 980,
                    href: "/photo-books",
                    id: "t2"
                }, {
                    top: 405,
                    left: 684,
                    height: 55,
                    width: 296,
                    href: "/yearbook",
                    id: "t2"
                }]
            }],
            tab3: [{
                src: "P41244_homepage_panel3_pg_MARQ_0403.jpg",
                layerImages: [{
                    top: 0,
                    left: 188,
                    height: 460,
                    width: 792,
                    href: "/photo-gifts/accessories-jewelry/jewelry",
                    id: "t3"
                }, {
                    top: 460,
                    left: 0,
                    height: 90,
                    width: 980,
                    href: "/photo-gifts/accessories-jewelry/jewelry",
                    id: "t3"
                }]
            }],
            tab4: [{
                src: "P26814_prospect_panel1_MARQ_0908.jpg",
                layerImages: [{
                    top: 285,
                    left: 449,
                    height: 39,
                    width: 91,
                    href: "/signup/viewSignup.sfly",
                    id: "t4"
                }, {
                    top: 285,
                    left: 555,
                    height: 39,
                    width: 54,
                    href: "/nav/mypics.sfly",
                    id: "t4"
                }, {
                    top: 490,
                    left: 323,
                    height: 61,
                    width: 102,
                    href: "/photo-gifts/magnets",
                    id: "t4"
                }, {
                    top: 490,
                    left: 424,
                    height: 61,
                    width: 162,
                    href: "/cards-stationery/address-labels",
                    id: "t4"
                }, {
                    top: 490,
                    left: 585,
                    height: 61,
                    width: 109,
                    href: "/prints/prints",
                    id: "t4"
                }]
            }]
        }
        };
        $("#marquee").marquee(t)
    })
</script>
@stop
    