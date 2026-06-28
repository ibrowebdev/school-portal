@extends('layouts.master')
@section('content')
<div class="space-y-6">
    <x-page-header title="Student Details" parent="Student" :parentRoute="route('students.create')" />

    <!-- Profile Header Card -->
    <x-card noPadding="true">
        <div class="relative">
            <!-- Background Image -->
            <div class="h-48 w-full bg-cover bg-center" style="background-image: url('{{ URL::to('assets/img/profile-bg.jpg') }}');"></div>
            
            <div class="px-6 py-4 flex flex-col md:flex-row items-center md:items-end md:-mt-12 gap-6 relative z-10">
                <!-- Profile Image -->
                <div class="relative shrink-0 -mt-20 md:mt-0">
                    <img src="{{ Storage::url('student-photos/'.$studentProfile->upload) }}" alt="Profile" class="w-32 h-32 rounded-full border-4 border-white shadow-md object-cover bg-white">
                    <label class="absolute bottom-0 right-0 w-8 h-8 bg-blue-600 text-white rounded-full flex items-center justify-center cursor-pointer shadow-sm hover:bg-blue-700 transition">
                        <i class="far fa-edit text-sm"></i>
                        <input type="file" class="hidden">
                    </label>
                </div>
                
                <!-- Profile Info -->
                <div class="flex-1 text-center md:text-left">
                    <h4 class="text-2xl font-bold text-gray-800">{{ $studentProfile->first_name }} {{ $studentProfile->last_name }}</h4>
                    <p class="text-gray-500 font-medium">Computer Science</p>
                </div>

                <!-- Stats -->
                <div class="flex items-center gap-6 text-center">
                    <div>
                        <h5 class="text-xs text-gray-500 uppercase font-bold tracking-wider">Followers</h5>
                        <h4 class="text-xl font-bold text-gray-800">2,850</h4>
                    </div>
                    <div>
                        <h5 class="text-xs text-gray-500 uppercase font-bold tracking-wider">Following</h5>
                        <h4 class="text-xl font-bold text-gray-800">340</h4>
                    </div>
                    <div>
                        <h5 class="text-xs text-gray-500 uppercase font-bold tracking-wider">Friends</h5>
                        <h4 class="text-xl font-bold text-gray-800">120</h4>
                    </div>
                </div>

                <!-- Actions -->
                <div class="flex items-center gap-3">
                    <button class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition shadow-sm font-medium">Follow</button>
                    <button class="px-6 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition shadow-sm font-medium">Message</button>
                </div>
            </div>
        </div>
    </x-card>

    <!-- Content Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Personal Details -->
            <x-card title="Personal Details">
                <ul class="space-y-4">
                    <li class="flex items-start gap-3 text-sm">
                        <div class="w-8 h-8 rounded-full bg-blue-50 text-blue-600 flex items-center justify-center shrink-0">
                            <i class="feather-user"></i>
                        </div>
                        <div>
                            <p class="text-gray-500 font-medium text-xs uppercase tracking-wider mb-0.5">Name</p>
                            <p class="text-gray-800 font-medium">{{ $studentProfile->first_name }} {{ $studentProfile->last_name }}</p>
                        </div>
                    </li>
                    <li class="flex items-start gap-3 text-sm">
                        <div class="w-8 h-8 rounded-full bg-blue-50 text-blue-600 flex items-center justify-center shrink-0">
                            <img src="{{ URL::to('assets/img/icons/buliding-icon.svg') }}" alt="" class="w-4 h-4 opacity-75">
                        </div>
                        <div>
                            <p class="text-gray-500 font-medium text-xs uppercase tracking-wider mb-0.5">Department</p>
                            <p class="text-gray-800 font-medium">Computer Science</p>
                        </div>
                    </li>
                    <li class="flex items-start gap-3 text-sm">
                        <div class="w-8 h-8 rounded-full bg-blue-50 text-blue-600 flex items-center justify-center shrink-0">
                            <i class="feather-phone-call"></i>
                        </div>
                        <div>
                            <p class="text-gray-500 font-medium text-xs uppercase tracking-wider mb-0.5">Mobile</p>
                            <p class="text-gray-800 font-medium">{{ $studentProfile->phone_number }}</p>
                        </div>
                    </li>
                    <li class="flex items-start gap-3 text-sm">
                        <div class="w-8 h-8 rounded-full bg-blue-50 text-blue-600 flex items-center justify-center shrink-0">
                            <i class="feather-mail"></i>
                        </div>
                        <div>
                            <p class="text-gray-500 font-medium text-xs uppercase tracking-wider mb-0.5">Email</p>
                            <a href="mailto:{{ $studentProfile->email }}" class="text-blue-600 hover:underline font-medium">{{ $studentProfile->email }}</a>
                        </div>
                    </li>
                    <li class="flex items-start gap-3 text-sm">
                        <div class="w-8 h-8 rounded-full bg-blue-50 text-blue-600 flex items-center justify-center shrink-0">
                            <i class="feather-user"></i>
                        </div>
                        <div>
                            <p class="text-gray-500 font-medium text-xs uppercase tracking-wider mb-0.5">Gender</p>
                            <p class="text-gray-800 font-medium">{{ $studentProfile->gender }}</p>
                        </div>
                    </li>
                    <li class="flex items-start gap-3 text-sm">
                        <div class="w-8 h-8 rounded-full bg-blue-50 text-blue-600 flex items-center justify-center shrink-0">
                            <i class="feather-calendar"></i>
                        </div>
                        <div>
                            <p class="text-gray-500 font-medium text-xs uppercase tracking-wider mb-0.5">Date of Birth</p>
                            <p class="text-gray-800 font-medium">{{ $studentProfile->date_of_birth }}</p>
                        </div>
                    </li>
                    <li class="flex items-start gap-3 text-sm">
                        <div class="w-8 h-8 rounded-full bg-blue-50 text-blue-600 flex items-center justify-center shrink-0">
                            <i class="feather-map-pin"></i>
                        </div>
                        <div>
                            <p class="text-gray-500 font-medium text-xs uppercase tracking-wider mb-0.5">Address</p>
                            <p class="text-gray-800 font-medium">480, Estern Avenue, New York</p>
                        </div>
                    </li>
                </ul>
            </x-card>

            <!-- Skills -->
            <x-card title="Skills">
                <div class="space-y-4">
                    <div>
                        <div class="flex justify-between text-sm mb-1">
                            <span class="font-medium text-gray-700">Photoshop</span>
                            <span class="text-gray-500">90%</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2">
                            <div class="bg-blue-600 h-2 rounded-full" style="width: 90%"></div>
                        </div>
                    </div>
                    <div>
                        <div class="flex justify-between text-sm mb-1">
                            <span class="font-medium text-gray-700">Code Editor</span>
                            <span class="text-gray-500">75%</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2">
                            <div class="bg-indigo-600 h-2 rounded-full" style="width: 75%"></div>
                        </div>
                    </div>
                    <div>
                        <div class="flex justify-between text-sm mb-1">
                            <span class="font-medium text-gray-700">Illustrator</span>
                            <span class="text-gray-500">95%</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2">
                            <div class="bg-pink-600 h-2 rounded-full" style="width: 95%"></div>
                        </div>
                    </div>
                </div>
            </x-card>
        </div>

        <!-- Main Content -->
        <div class="lg:col-span-2 space-y-6">
            <x-card title="About Me">
                <div class="prose max-w-none text-sm text-gray-600">
                    <h5 class="text-base font-bold text-gray-800 mb-2">Hello I am {{ $studentProfile->first_name }} {{ $studentProfile->last_name }}</h5>
                    <p class="mb-4">Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex commodo consequat.</p>
                    <p>Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, totam inventore veritatis et quasi architecto beatae vitae dicta sunt explicabo.</p>
                </div>
                
                <hr class="my-6 border-gray-100">
                
                <h5 class="text-base font-bold text-gray-800 mb-4">Education</h5>
                <div class="space-y-4">
                    <div class="relative pl-4 border-l-2 border-blue-500 pb-4">
                        <div class="absolute w-3 h-3 bg-white border-2 border-blue-500 rounded-full -left-[7px] top-1"></div>
                        <h6 class="text-sm font-bold text-gray-800 mb-1">2008 - 2009</h6>
                        <p class="text-sm text-gray-600">Secondary Schooling at xyz school of secondary education, Mumbai.</p>
                    </div>
                    <div class="relative pl-4 border-l-2 border-blue-500 pb-4">
                        <div class="absolute w-3 h-3 bg-white border-2 border-blue-500 rounded-full -left-[7px] top-1"></div>
                        <h6 class="text-sm font-bold text-gray-800 mb-1">2011 - 2012</h6>
                        <p class="text-sm text-gray-600">Higher Secondary Schooling at xyz school of higher secondary education, Mumbai.</p>
                    </div>
                    <div class="relative pl-4 border-l-2 border-blue-500 pb-4">
                        <div class="absolute w-3 h-3 bg-white border-2 border-blue-500 rounded-full -left-[7px] top-1"></div>
                        <h6 class="text-sm font-bold text-gray-800 mb-1">2012 - 2015</h6>
                        <p class="text-sm text-gray-600">Bachelor of Science at Abc College of Art and Science, Chennai.</p>
                    </div>
                    <div class="relative pl-4 border-l-2 border-transparent">
                        <div class="absolute w-3 h-3 bg-white border-2 border-blue-500 rounded-full -left-[7px] top-1"></div>
                        <h6 class="text-sm font-bold text-gray-800 mb-1">2015 - 2017</h6>
                        <p class="text-sm text-gray-600">Master of Science at Cdm College of Engineering and Technology, Pune.</p>
                    </div>
                </div>
            </x-card>
        </div>
    </div>
</div>
@endsection
