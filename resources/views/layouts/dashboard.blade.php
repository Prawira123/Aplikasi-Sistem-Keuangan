<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bengkel Kembang Motor App</title>
    <link rel="icon" href="{{ asset('images/logo.png') }}" type="image/png">
    <link rel="stylesheet" href="{{ asset('dist/assets/compiled/css/app.css') }}">
    <link rel="stylesheet" href="{{ asset('dist/assets/compiled/css/app-dark.css') }}">
    <link rel="stylesheet" href="{{ asset('dist/assets/compiled/css/iconly.css') }}">
    <link rel="stylesheet" href="{{ asset('dist/assets/extensions/simple-datatables/style.css')}}">
    <link rel="stylesheet" href="{{ asset('dist/assets/compiled/css/table-datatable.css') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">

</head>

<body>
    <script src="assets/static/js/initTheme.js"></script>
    <div id="app">
        <div id="sidebar">
            <div class="sidebar-wrapper active">
    <div class="sidebar-header position-relative">
        <div class="d-flex justify-content-between align-items-center">
            <div class="logo">
                {{-- <a href="{{ route('dashboard.index') }}"><img src="{{ asset('images/fulllogo.png') }}" alt="Logo" width="120" class="object-fit-contain"></a> --}}
                <a href="{{ route('dashboard.index') }}" class="d-flex align-items-center">
    <img
        src="{{ asset('images/fulllogo.png') }}"
        alt="Logo"
        style="height:auto; width:130px; object-fit:cover;">
</a>
            </div>
            <div class="theme-toggle d-flex gap-2  align-items-center mt-2">
                <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" aria-hidden="true"
                    role="img" class="iconify iconify--system-uicons" width="20" height="20"
                    preserveAspectRatio="xMidYMid meet" viewBox="0 0 21 21">
                    <g fill="none" fill-rule="evenodd" stroke="currentColor" stroke-linecap="round"
                        stroke-linejoin="round">
                        <path
                            d="M10.5 14.5c2.219 0 4-1.763 4-3.982a4.003 4.003 0 0 0-4-4.018c-2.219 0-4 1.781-4 4c0 2.219 1.781 4 4 4zM4.136 4.136L5.55 5.55m9.9 9.9l1.414 1.414M1.5 10.5h2m14 0h2M4.135 16.863L5.55 15.45m9.899-9.9l1.414-1.415M10.5 19.5v-2m0-14v-2"
                            opacity=".3"></path>
                        <g transform="translate(-210 -1)">
                            <path d="M220.5 2.5v2m6.5.5l-1.5 1.5"></path>
                            <circle cx="220.5" cy="11.5" r="4"></circle>
                            <path d="m214 5l1.5 1.5m5 14v-2m6.5-.5l-1.5-1.5M214 18l1.5-1.5m-4-5h2m14 0h2"></path>
                        </g>
                    </g>
                </svg>
                <div class="form-check form-switch fs-6">
                    <input class="form-check-input  me-0" type="checkbox" id="toggle-dark" style="cursor: pointer">
                    <label class="form-check-label"></label>
                </div>
                <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" aria-hidden="true"
                    role="img" class="iconify iconify--mdi" width="20" height="20" preserveAspectRatio="xMidYMid meet"
                    viewBox="0 0 24 24">
                    <path fill="currentColor"
                        d="m17.75 4.09l-2.53 1.94l.91 3.06l-2.63-1.81l-2.63 1.81l.91-3.06l-2.53-1.94L12.44 4l1.06-3l1.06 3l3.19.09m3.5 6.91l-1.64 1.25l.59 1.98l-1.7-1.17l-1.7 1.17l.59-1.98L15.75 11l2.06-.05L18.5 9l.69 1.95l2.06.05m-2.28 4.95c.83-.08 1.72 1.1 1.19 1.85c-.32.45-.66.87-1.08 1.27C15.17 23 8.84 23 4.94 19.07c-3.91-3.9-3.91-10.24 0-14.14c.4-.4.82-.76 1.27-1.08c.75-.53 1.93.36 1.85 1.19c-.27 2.86.69 5.83 2.89 8.02a9.96 9.96 0 0 0 8.02 2.89m-1.64 2.02a12.08 12.08 0 0 1-7.8-3.47c-2.17-2.19-3.33-5-3.49-7.82c-2.81 3.14-2.7 7.96.31 10.98c3.02 3.01 7.84 3.12 10.98.31Z">
                    </path>
                </svg>
            </div>
            <div class="sidebar-toggler  x">
                <a href="#" class="sidebar-hide d-xl-none d-block"><i class="bi bi-x bi-middle"></i></a>
            </div>
        </div>
    </div>
<div class="sidebar-menu">
    <ul class="menu">            
        <li class="sidebar-title">Menu</li>
            @if(session('role') == 'owner')
                <li class="sidebar-item {{ request()->is('dashboard') ? 'active' : '' }}" id="sidebar-item">
                    <a href="{{ route('dashboard.index') }}" class='sidebar-link' id="sidebar-link">
                        <i class="bi bi-grid-fill"></i>
                        <span>Dashboard</span>
                    </a>
                </li>
                <li class="sidebar-item  has-sub">
                    <a href="#" class='sidebar-link'>
                        <i class="bi bi-stack"></i>
                        <span>Data Master</span>
                    </a>
                    
                    <ul class="submenu ">
                        <li class="submenu-item {{ request()->is('users*') ? 'active' : '' }} ">
                            <a href="{{ route('users.index') }}" class="submenu-link">User</a>  
                        </li>
                        <li class="submenu-item {{ request()->is('suppliers*') ? 'active' : '' }} ">
                            <a href="{{ route('suppliers.index') }}" class="submenu-link">Supplier</a>
                        </li>
                        <li class="submenu-item {{ request()->is('products*') ? 'active' : '' }} ">
                            <a href="{{ route('products.index') }}" class="submenu-link">Barang</a>
                        </li>
                        <li class="submenu-item {{ request()->is('jasas*') ? 'active' : '' }} ">
                            <a href="{{ route('jasas.index') }}" class="submenu-link">Jasa</a>
                        </li>
                        <li class="submenu-item {{ request()->is('pakets*') ? 'active' : '' }} ">
                            <a href="{{ route('pakets.index') }}" class="submenu-link">Paket</a>
                        </li>
                        <li class="submenu-item {{ request()->is('akuns*') ? 'active' : '' }} ">
                            <a href="{{ route('akuns.index') }}" class="submenu-link">Akun</a>
                        </li>
                        <li class="submenu-item {{ request()->is('kategories*') ? 'active' : '' }} ">
                            <a href="{{ route('kategories.index') }}" class="submenu-link">Kategori Akun</a>  
                        </li>
                        <li class="submenu-item {{ request()->is('karyawans*') ? 'active' : '' }} ">
                            <a href="{{ route('karyawans.index') }}" class="submenu-link">Karyawan</a>  
                        </li>
                        <li class="submenu-item {{ request()->is('pelanggans*') ? 'active' : '' }} ">
                            <a href="{{ route('pelanggans.index') }}" class="submenu-link">Pelanggan</a>  
                        </li>
                    </ul>
                </li>
                <li class="sidebar-item {{ request()->is('transaksi_masuks*') ? 'active' : '' }}" id="sidebar-item">
                    <a href="{{ route('transaksi_masuks.index') }}" class='sidebar-link' id="sidebar-link">
                        <i class="bi bi-cash-stack"></i>
                        <span>Data Penjualan</span>
                    </a>
                </li>
                <li class="sidebar-item {{ request()->is('transaksi_keluars*') ? 'active' : '' }}" id="sidebar-item">
                    <a href="{{ route('transaksi_keluars.index') }}" class='sidebar-link' id="sidebar-link">
                        <i class="bi bi-bag-plus-fill"></i>
                        <span>Data Pembelian</span>
                    </a>
                </li>
                <li class="sidebar-item {{ request()->is('jurnal_entries*') ? 'active' : '' }}" id="sidebar-item">
                    <a href="{{ route('jurnal_entries.index') }}" class='sidebar-link' id="sidebar-link">
                        <i class="bi bi-arrow-down-right-square-fill"></i>
                        <span>Jurnal Entry</span>
                    </a>
                </li>            
                <li class="sidebar-item {{ request()->is('laporans*') ? 'active' : '' }}" id="sidebar-item">
                    <a href="{{ route('laporans.index') }}" class='sidebar-link' id="sidebar-link">
                        <i class="bi bi-journal-text"></i>
                        <span>Laporan</span>
                    </a>
                </li>
                <li class="sidebar-item {{ request()->is('gaji_karyawans*') ? 'active' : '' }}" id="sidebar-item">
                    <a href="{{ route('gaji_karyawans.index') }}" class='sidebar-link' id="sidebar-link">
                        <i class="bi bi-people-fill"></i>
                        <span>Penggajian Karyawan</span>
                    </a>
                </li>
                <li class="sidebar-item">
                    <a href="{{ route('logout') }}" class="sidebar-link"
                        onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        <i class="bi bi-box-arrow-right"></i>
                        <span>Logout</span>
                    </a>

                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                        @csrf
                    </form>
                </li>
            @endif
            @if(session('role') == 'admin')
                <li class="sidebar-item {{ request()->is('dashboard') ? 'active' : '' }}" id="sidebar-item">
                    <a href="{{ route('dashboard.index') }}" class='sidebar-link' id="sidebar-link">
                        <i class="bi bi-grid-fill"></i>
                        <span>Dashboard</span>
                    </a>
                </li>
                <li class="sidebar-item  has-sub">
                    <a href="#" class='sidebar-link'>
                        <i class="bi bi-stack"></i>
                        <span>Data Master</span>
                    </a>
                    
                    <ul class="submenu ">
                        <li class="submenu-item {{ request()->is('suppliers*') ? 'active' : '' }} ">
                            <a href="{{ route('suppliers.index') }}" class="submenu-link">Supplier</a>
                        </li>
                        <li class="submenu-item {{ request()->is('products*') ? 'active' : '' }} ">
                            <a href="{{ route('products.index') }}" class="submenu-link">Barang</a>
                        </li>
                        <li class="submenu-item {{ request()->is('jasas*') ? 'active' : '' }} ">
                            <a href="{{ route('jasas.index') }}" class="submenu-link">Jasa</a>
                        </li>
                        <li class="submenu-item {{ request()->is('pakets*') ? 'active' : '' }} ">
                            <a href="{{ route('pakets.index') }}" class="submenu-link">Paket</a>
                        </li>
                        <li class="submenu-item {{ request()->is('akuns*') ? 'active' : '' }} ">
                            <a href="{{ route('akuns.index') }}" class="submenu-link">Akun</a>
                        </li>
                        <li class="submenu-item {{ request()->is('kategories*') ? 'active' : '' }} ">
                            <a href="{{ route('kategories.index') }}" class="submenu-link">Kategori Akun</a>  
                        </li>
                        <li class="submenu-item {{ request()->is('karyawans*') ? 'active' : '' }} ">
                            <a href="{{ route('karyawans.index') }}" class="submenu-link">Karyawan</a>  
                        </li>
                        <li class="submenu-item {{ request()->is('pelanggans*') ? 'active' : '' }} ">
                            <a href="{{ route('pelanggans.index') }}" class="submenu-link">Pelanggan</a>  
                        </li>
                    </ul>
                </li>
                <li class="sidebar-item {{ request()->is('transaksi_masuks*') ? 'active' : '' }}" id="sidebar-item">
                    <a href="{{ route('transaksi_masuks.index') }}" class='sidebar-link' id="sidebar-link">
                        <i class="bi bi-cash-stack"></i>
                        <span>Data Penjualan</span>
                    </a>
                </li>
                <li class="sidebar-item {{ request()->is('transaksi_keluars*') ? 'active' : '' }}" id="sidebar-item">
                    <a href="{{ route('transaksi_keluars.index') }}" class='sidebar-link' id="sidebar-link">
                        <i class="bi bi-bag-plus-fill"></i>
                        <span>Data Pembelian</span>
                    </a>
                </li>
                <li class="sidebar-item {{ request()->is('jurnal_entries*') ? 'active' : '' }}" id="sidebar-item">
                    <a href="{{ route('jurnal_entries.index') }}" class='sidebar-link' id="sidebar-link">
                        <i class="bi bi-arrow-down-right-square-fill"></i>
                        <span>Jurnal Entry</span>
                    </a>
                </li>            
                <li class="sidebar-item">
                    <a href="{{ route('logout') }}" class="sidebar-link"
                        onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        <i class="bi bi-box-arrow-right"></i>
                        <span>Logout</span>
                    </a>

                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                        @csrf
                    </form>
                </li>
            @endif
        </ul>
    </div>
</div>
</div>
    <div id="main">


        
        @yield('content')

    </div>
</div>

<script src="{{  asset('dist/assets/static/js/components/dark.js')}}"></script>
<script src="{{  asset('dist/assets/extensions/perfect-scrollbar/perfect-scrollbar.min.js')}}"></script>
<script src="{{  asset('dist/assets/compiled/js/app.js')}}"></script>
<script src="{{  asset('dist/assets/extensions/apexcharts/apexcharts.min.js')}}"></script>
<script src="{{  asset('dist/assets/extensions/chart.js/chart.umd.js')}}"></script>
<script src="{{  asset('dist/assets/static/js/pages/dashboard.js')}}"></script>
<script src="{{ asset('dist/assets/extensions/simple-datatables/umd/simple-datatables.js') }}"></script>
<script src="{{  asset('dist/assets/static/js/pages/simple-datatables.js')}}"></script>


<!-- untuk date -->
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script>
    let datetime = flatpickr('.datetime', {
        enableTime: true,
        format: "Y-m-d"
    })

    let date = flatpickr('.date', {
        enableTime: false,
        format: "Y-m-d"
    })

    let ctxBar = document.getElementById('chart-perdapatan-perbulan').getContext('2d');
    let myBar = new Chart(ctxBar, {
        type: 'bar',
        data: {
            labels: ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'],
            datasets: [{
                label: 'Total Penjualan',
                data: [],
                backgroundColor: '#3f51b5',
                borderColor: '#3f51b5',
            }]
        },
        options:{
            responsive: true,
            title : {
                display: true,
                text: 'Grafik Penjualan Perbulan'
            },
            scales: {
                y:{
                    beginAtZero: true
                }
            }
        }
    });

    // let ctxBar2 = document.getElementById('chart-barang').getContext('2d');
    // let productBar = new Chart(ctxBar2, {
    //     type: 'line',
    //     data: {
    //         labels: ['Jan','Feb','Mar','Apr','Mei','Jun'],
    //         datasets: [{
    //         data: [],
    //         borderColor: '#5B5CFF',
    //         backgroundColor: 'rgba(91,92,255,0.2)',
    //         fill: true,
    //         tension: 0.4,
    //         pointRadius: 0
    //         }]
    //     },
    //     options: {
    //         responsive: true,
    //         plugins: {
    //         legend: { display: false },
    //         tooltip: { enabled: true }
    //         },
    //         scales: {
    //         x: { display: false },
    //         y: { display: false }
    //         }
    //     }
    // });

    // let ctxBar3 = document.getElementById('chart-jasa').getContext('2d');
    // let jasaBar = new Chart(ctxBar3, {
    //     type: 'line',
    //     data: {
    //         labels: ['Jan','Feb','Mar','Apr','Mei','Jun'],
    //         datasets: [{
    //         data: [],
    //         borderColor: '#1cc88a',
    //         backgroundColor: 'rgba(91,92,255,0.2)',
    //         fill: true,
    //         tension: 0.4,
    //         pointRadius: 0
    //         }]
    //     },
    //     options: {
    //         responsive: true,
    //         plugins: {
    //         legend: { display: false },
    //         tooltip: { enabled: true }
    //         },
    //         scales: {
    //         x: { display: false },
    //         y: { display: false }
    //         }
    //     }
    // });

    // let ctxBar4 = document.getElementById('chart-paket').getContext('2d');
    // let paketBar = new Chart(ctxBar4, {
    //     type: 'line',
    //     data: {
    //         labels: ['Jan','Feb','Mar','Apr','Mei','Jun'],
    //         datasets: [{
    //         data: [],
    //         borderColor: '#ff6384',
    //         backgroundColor: 'rgba(91,92,255,0.2)',
    //         fill: true,
    //         tension: 0.4,
    //         pointRadius: 0
    //         }]
    //     },
    //     options: {
    //         responsive: true,
    //         plugins: {
    //         legend: { display: false },
    //         tooltip: { enabled: true }
    //         },
    //         scales: {
    //         x: { display: false },
    //         y: { display: false }
    //         }
    //     }
    // });

    let ctxDonut = document
    .getElementById('chart-donut-penjualan')
    .getContext('2d');

    let donutChart = new Chart(ctxDonut, {
        type: 'doughnut',
        data: {
            labels: ['Barang', 'Jasa', 'Paket'],
            datasets: [{
                data: [], // diisi dari API
                backgroundColor: [
                    '#4e73df',
                    '#1cc88a',
                    '#e74a3b'
                ],
                borderWidth: 2
            }]
        },
        options: {
            responsive: true,
            cutout: '30%', // bikin donut
            plugins: {
                legend: {
                    position: 'bottom',
                    align: 'left',
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            let value = context.raw || 0;
                            return context.label + ': Rp ' + value.toLocaleString('id-ID');
                        }
                    }
                }
            }
        }
    });

    function updateData() {
        fetch('/dashboard/penjualan_perbulan')
            .then(response => response.json())
            .then(output => {
                // isi label & data
                myBar.data.labels = output.labels;
                myBar.data.datasets[0].data = output.data;
                myBar.update();
            })
            .catch(err => console.error(err));
    }

    function updateDataDetail(){
        fetch('/dashboard/penjualan_perbulan_detail')
        .then(response =>response.json())
        .then(output => {
            // isi label & data
            // productBar.data.labels = output.labels;
            // productBar.data.datasets[0].data = output.data['Barang'];
            // productBar.update();

            // jasaBar.data.labels = output.labels;
            // jasaBar.data.datasets[0].data = output.data['Jasa'];
            // jasaBar.update();

            // paketBar.data.labels = output.labels;
            // paketBar.data.datasets[0].data = output.data['Paket'];
            // paketBar.update();

            donutChart.data.labels = ['Barang', 'Jasa', 'Paket'];
            donutChart.data.datasets[0].data = [
                output.data.Barang.reduce((total, value) => total + value, 0),
                output.data.Jasa.reduce((total, value) => total + value, 0),
                output.data.Paket.reduce((total, value) => total + value, 0)
            ];
            donutChart.update();

            console.log('DONUT:', donutChart.data.datasets[0].data);

            console.log(output);
        })
    }

    updateDataDetail();
    updateData();

</script>
</body>
</html>