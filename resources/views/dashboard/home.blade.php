@extends('layouts.master')
@section('content')
<div class="space-y-6">
    <!-- Page Header -->
    <div class="flex items-center justify-between">
        <div>
            <h3 class="text-2xl font-bold text-gray-800">Welcome {{ Session::get('name') }}!</h3>
            <div class="flex items-center text-sm text-gray-500 mt-1">
                <a href="{{ route('home') }}" class="hover:text-blue-600 transition">Home</a>
                <span class="mx-2">/</span>
                <span class="text-gray-800 font-medium">{{ Session::get('name') }}</span>
            </div>
        </div>
    </div>

    <!-- Stat Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Students -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-500 uppercase tracking-wider">Students</p>
                <h3 class="text-2xl font-bold text-gray-800 mt-1">50,055</h3>
            </div>
            <div class="w-12 h-12 bg-blue-50 rounded-lg flex items-center justify-center">
                <img src="{{ URL::to('assets/img/icons/dash-icon-01.svg') }}" class="w-6 h-6" alt="Icon">
            </div>
        </div>
        <!-- Awards -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-500 uppercase tracking-wider">Awards</p>
                <h3 class="text-2xl font-bold text-gray-800 mt-1">50+</h3>
            </div>
            <div class="w-12 h-12 bg-purple-50 rounded-lg flex items-center justify-center">
                <img src="{{ URL::to('assets/img/icons/dash-icon-02.svg') }}" class="w-6 h-6" alt="Icon">
            </div>
        </div>
        <!-- Department -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-500 uppercase tracking-wider">Department</p>
                <h3 class="text-2xl font-bold text-gray-800 mt-1">30+</h3>
            </div>
            <div class="w-12 h-12 bg-amber-50 rounded-lg flex items-center justify-center">
                <img src="{{ URL::to('assets/img/icons/dash-icon-03.svg') }}" class="w-6 h-6" alt="Icon">
            </div>
        </div>
        <!-- Revenue -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-500 uppercase tracking-wider">Revenue</p>
                <h3 class="text-2xl font-bold text-gray-800 mt-1">$505</h3>
            </div>
            <div class="w-12 h-12 bg-green-50 rounded-lg flex items-center justify-center">
                <img src="{{ URL::to('assets/img/icons/dash-icon-04.svg') }}" class="w-6 h-6" alt="Icon">
            </div>
        </div>
    </div>

    <!-- Charts -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Overview Chart -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                <h5 class="font-bold text-gray-800">Overview</h5>
                <div class="flex items-center gap-4 text-sm">
                    <div class="flex items-center gap-2"><span class="w-3 h-3 rounded-full bg-blue-500"></span> Teacher</div>
                    <div class="flex items-center gap-2"><span class="w-3 h-3 rounded-full bg-green-500"></span> Student</div>
                    <button class="text-gray-400 hover:text-gray-600"><i class="fas fa-ellipsis-v"></i></button>
                </div>
            </div>
            <div class="p-6">
                <div id="apexcharts-area" class="h-[300px]"></div>
            </div>
        </div>
        <!-- Students Chart -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                <h5 class="font-bold text-gray-800">Number of Students</h5>
                <div class="flex items-center gap-4 text-sm">
                    <div class="flex items-center gap-2"><span class="w-3 h-3 rounded-full bg-blue-500"></span> Girls</div>
                    <div class="flex items-center gap-2"><span class="w-3 h-3 rounded-full bg-green-500"></span> Boys</div>
                    <button class="text-gray-400 hover:text-gray-600"><i class="fas fa-ellipsis-v"></i></button>
                </div>
            </div>
            <div class="p-6">
                <div id="bar" class="h-[300px]"></div>
            </div>
        </div>
    </div>

    <!-- Activities -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Star Students -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                <h5 class="font-bold text-gray-800">Star Students</h5>
                <button class="text-gray-400 hover:text-gray-600"><i class="fas fa-ellipsis-v"></i></button>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left">
                    <thead class="bg-gray-50 text-gray-600 font-medium border-b border-gray-100">
                        <tr>
                            <th class="px-6 py-3">ID</th>
                            <th class="px-6 py-3">Name</th>
                            <th class="px-6 py-3 text-center">Marks</th>
                            <th class="px-6 py-3 text-center">Percentage</th>
                            <th class="px-6 py-3 text-right">Year</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-6 py-4 text-gray-500 font-medium">PRE2209</td>
                            <td class="px-6 py-4 flex items-center gap-3">
                                <img class="rounded-full w-8 h-8 object-cover border border-gray-200" src="{{ URL::to('assets/img/profiles/avatar-01.jpg') }}" alt="Student">
                                <span class="font-medium text-gray-800">Soeng Souy</span>
                            </td>
                            <td class="px-6 py-4 text-center">1185</td>
                            <td class="px-6 py-4 text-center font-medium text-green-600">98%</td>
                            <td class="px-6 py-4 text-right text-gray-500">2019</td>
                        </tr>
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-6 py-4 text-gray-500 font-medium">PRE1245</td>
                            <td class="px-6 py-4 flex items-center gap-3">
                                <img class="rounded-full w-8 h-8 object-cover border border-gray-200" src="{{ URL::to('assets/img/profiles/avatar-01.jpg') }}" alt="Student">
                                <span class="font-medium text-gray-800">Soeng Souy</span>
                            </td>
                            <td class="px-6 py-4 text-center">1195</td>
                            <td class="px-6 py-4 text-center font-medium text-green-600">99.5%</td>
                            <td class="px-6 py-4 text-right text-gray-500">2018</td>
                        </tr>
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-6 py-4 text-gray-500 font-medium">PRE1625</td>
                            <td class="px-6 py-4 flex items-center gap-3">
                                <img class="rounded-full w-8 h-8 object-cover border border-gray-200" src="{{ URL::to('assets/img/profiles/avatar-01.jpg') }}" alt="Student">
                                <span class="font-medium text-gray-800">Soeng Souy</span>
                            </td>
                            <td class="px-6 py-4 text-center">1196</td>
                            <td class="px-6 py-4 text-center font-medium text-green-600">99.6%</td>
                            <td class="px-6 py-4 text-right text-gray-500">2017</td>
                        </tr>
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-6 py-4 text-gray-500 font-medium">PRE2516</td>
                            <td class="px-6 py-4 flex items-center gap-3">
                                <img class="rounded-full w-8 h-8 object-cover border border-gray-200" src="{{ URL::to('assets/img/profiles/avatar-01.jpg') }}" alt="Student">
                                <span class="font-medium text-gray-800">Soeng Souy</span>
                            </td>
                            <td class="px-6 py-4 text-center">1187</td>
                            <td class="px-6 py-4 text-center font-medium text-green-600">98.2%</td>
                            <td class="px-6 py-4 text-right text-gray-500">2016</td>
                        </tr>
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-6 py-4 text-gray-500 font-medium">PRE2209</td>
                            <td class="px-6 py-4 flex items-center gap-3">
                                <img class="rounded-full w-8 h-8 object-cover border border-gray-200" src="{{ URL::to('assets/img/profiles/avatar-01.jpg') }}" alt="Student">
                                <span class="font-medium text-gray-800">Soeng Souy</span>
                            </td>
                            <td class="px-6 py-4 text-center">1185</td>
                            <td class="px-6 py-4 text-center font-medium text-green-600">98%</td>
                            <td class="px-6 py-4 text-right text-gray-500">2015</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Student Activity -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                <h5 class="font-bold text-gray-800">Student Activity</h5>
                <button class="text-gray-400 hover:text-gray-600"><i class="fas fa-ellipsis-v"></i></button>
            </div>
            <div class="p-6">
                <ul class="space-y-6 relative before:absolute before:inset-y-0 before:left-5 before:w-0.5 before:bg-gray-100">
                    <li class="relative flex items-start gap-4">
                        <div class="w-10 h-10 rounded-full bg-blue-50 border-4 border-white flex items-center justify-center shrink-0 z-10 shadow-sm">
                            <img src="assets/img/icons/award-icon-01.svg" class="w-5 h-5" alt="Award">
                        </div>
                        <div>
                            <h4 class="text-sm font-semibold text-gray-800">1st place in "Chess"</h4>
                            <p class="text-xs text-gray-500 mt-1">John Doe won 1st place in "Chess"</p>
                            <span class="text-xs text-blue-500 font-medium mt-1 inline-block">1 Day ago</span>
                        </div>
                    </li>
                    <li class="relative flex items-start gap-4">
                        <div class="w-10 h-10 rounded-full bg-purple-50 border-4 border-white flex items-center justify-center shrink-0 z-10 shadow-sm">
                            <img src="assets/img/icons/award-icon-02.svg" class="w-5 h-5" alt="Award">
                        </div>
                        <div>
                            <h4 class="text-sm font-semibold text-gray-800">Participated in "Carrom"</h4>
                            <p class="text-xs text-gray-500 mt-1">Justin Lee participated in "Carrom"</p>
                            <span class="text-xs text-blue-500 font-medium mt-1 inline-block">2 hours ago</span>
                        </div>
                    </li>
                    <li class="relative flex items-start gap-4">
                        <div class="w-10 h-10 rounded-full bg-amber-50 border-4 border-white flex items-center justify-center shrink-0 z-10 shadow-sm">
                            <img src="assets/img/icons/award-icon-03.svg" class="w-5 h-5" alt="Award">
                        </div>
                        <div>
                            <h4 class="text-sm font-semibold text-gray-800">Internation conference</h4>
                            <p class="text-xs text-gray-500 mt-1">Justin Lee attended internation conference in "St.John School"</p>
                            <span class="text-xs text-blue-500 font-medium mt-1 inline-block">2 Weeks ago</span>
                        </div>
                    </li>
                    <li class="relative flex items-start gap-4">
                        <div class="w-10 h-10 rounded-full bg-green-50 border-4 border-white flex items-center justify-center shrink-0 z-10 shadow-sm">
                            <img src="assets/img/icons/award-icon-04.svg" class="w-5 h-5" alt="Award">
                        </div>
                        <div>
                            <h4 class="text-sm font-semibold text-gray-800">Won 1st place in "Chess"</h4>
                            <p class="text-xs text-gray-500 mt-1">John Doe won 1st place in "Chess"</p>
                            <span class="text-xs text-blue-500 font-medium mt-1 inline-block">3 Days ago</span>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </div>

    <!-- Social Stats -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 flex items-center justify-between group cursor-pointer hover:shadow-md transition">
            <div>
                <p class="text-xs font-medium text-gray-500 uppercase tracking-wider group-hover:text-blue-600 transition">Like us on facebook</p>
                <h6 class="text-xl font-bold text-gray-800 mt-1">50,095</h6>
            </div>
            <div class="w-12 h-12 bg-blue-50 rounded-full flex items-center justify-center text-blue-600 text-xl group-hover:bg-blue-600 group-hover:text-white transition">
                <i class="fab fa-facebook-f"></i>
            </div>
        </div>
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 flex items-center justify-between group cursor-pointer hover:shadow-md transition">
            <div>
                <p class="text-xs font-medium text-gray-500 uppercase tracking-wider group-hover:text-sky-500 transition">Follow us on twitter</p>
                <h6 class="text-xl font-bold text-gray-800 mt-1">48,596</h6>
            </div>
            <div class="w-12 h-12 bg-sky-50 rounded-full flex items-center justify-center text-sky-500 text-xl group-hover:bg-sky-500 group-hover:text-white transition">
                <i class="fab fa-twitter"></i>
            </div>
        </div>
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 flex items-center justify-between group cursor-pointer hover:shadow-md transition">
            <div>
                <p class="text-xs font-medium text-gray-500 uppercase tracking-wider group-hover:text-pink-600 transition">Follow us on instagram</p>
                <h6 class="text-xl font-bold text-gray-800 mt-1">52,085</h6>
            </div>
            <div class="w-12 h-12 bg-pink-50 rounded-full flex items-center justify-center text-pink-600 text-xl group-hover:bg-pink-600 group-hover:text-white transition">
                <i class="fab fa-instagram"></i>
            </div>
        </div>
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 flex items-center justify-between group cursor-pointer hover:shadow-md transition">
            <div>
                <p class="text-xs font-medium text-gray-500 uppercase tracking-wider group-hover:text-blue-700 transition">Follow us on linkedin</p>
                <h6 class="text-xl font-bold text-gray-800 mt-1">69,050</h6>
            </div>
            <div class="w-12 h-12 bg-blue-50 rounded-full flex items-center justify-center text-blue-700 text-xl group-hover:bg-blue-700 group-hover:text-white transition">
                <i class="fab fa-linkedin-in"></i>
            </div>
        </div>
    </div>
</div>
@endsection
