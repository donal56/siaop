<?php
    $this->title = 'Dashboard';
?>
<div class="row">
    <div class="col-xl-6">
        <div class="row">
            <div class="col-xl-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row separate-row">
                            <div class="col-sm-6">
                                <div class="job-icon pb-4 d-flex justify-content-between">
                                    <div>
                                        <div class="d-flex align-items-center mb-1">
                                            <h2 class="mb-0">342</h2>
                                            <span class="text-success ms-3">+0.5 %</span>
                                        </div>	
                                        <span class="fs-14 d-block mb-2">Interview Schedules</span>
                                    </div>	
                                    <div id="NewCustomers"></div>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="job-icon pb-4 pt-4 pt-sm-0 d-flex justify-content-between">
                                    <div>
                                        <div class="d-flex align-items-center mb-1">
                                            <h2 class="mb-0">984</h2>
                                        </div>	
                                        <span class="fs-14 d-block mb-2">Application Sent</span>
                                    </div>	
                                    <div id="NewCustomers1"></div>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="job-icon pt-4 pb-sm-0 pb-4 pe-3 d-flex justify-content-between">
                                    <div>
                                        <div class="d-flex align-items-center mb-1">
                                            <h2 class="mb-0">1,567k</h2>
                                            <span class="text-danger ms-3">-2 % </span>
                                        </div>	
                                        <span class="fs-14 d-block mb-2">Profile Viewed</span>
                                    </div>	
                                    <div id="NewCustomers2"></div>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="job-icon pt-4  d-flex justify-content-between">
                                    <div>
                                        <div class="d-flex align-items-center mb-1">
                                            <h2 class="mb-0">437</h2>
                                        </div>	
                                        <span class="fs-14 d-block mb-2">Unread Messages</span>
                                    </div>	
                                    <div id="NewCustomers3"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-12">
                <div class="card "  id="user-activity">
                    <div class="card-header border-0 pb-0 flex-wrap">
                        <h4 class="fs-20 mb-1">Vacany Stats</h4>
                        <div class="card-action coin-tabs mt-3 mt-sm-0">
                            <ul class="nav nav-tabs" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link " data-bs-toggle="tab" href="#Daily" role="tab">Daily</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link " data-bs-toggle="tab" href="#weekly" role="tab" >Weekly</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link active" data-bs-toggle="tab" href="#monthly" role="tab" >Monthly</a>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="pb-4 d-flex flex-wrap">
                            <span class="d-flex align-items-center">
                                <svg class="me-1" xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 13 13">
                                    <rect  width="13" height="13" rx="6.5" fill="#35c556"/>
                                </svg>
                                Application Sent	
                            </span>
                            <span class="application d-flex align-items-center">
                                <svg class="me-1" xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 13 13">
                                    <rect  width="13" height="13" rx="6.5" fill="#3f4cfe"/>
                                </svg>

                                Interviews	
                            </span>
                            <span class="application d-flex align-items-center">
                                <svg class="me-1" xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 13 13">
                                    <rect  width="13" height="13" rx="6.5" fill="#f34040"/>
                                </svg>

                                Rejected
                            </span>
                        </div>
                        <div class="tab-content">
                            <div class="tab-pane fade show active" id="Daily">
                                <canvas id="activity" height="115"></canvas>
                            </div>	
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-12">
                <div class="card" id="user-activity1">
                    <div class="card-header border-0 pb-0">
                        <h4 class="fs-20 mb-1">Chart</h4>
                        <div class="card-action coin-tabs mt-3 mt-sm-0">
                            <ul class="nav nav-tabs" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link " data-bs-toggle="tab" href="#Daily1" role="tab">Daily</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link " data-bs-toggle="tab" href="#weekly1" role="tab" >Weekly</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link active" data-bs-toggle="tab" href="#monthly1" role="tab" >Monthly</a>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="card-body">
                        <span class="me-sm-5 me-3 font-w500">
                            <svg class="me-1" xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 13 13">
                                <rect  width="13" height="13" fill="#f73a0b"/>
                            </svg>
                            Delivered
                        </span>
                        <span class="fs-16 font-w600 me-5">239 <small class="text-success fs-12 font-w400">+0.4%</small></span>
                        <span class="ms-sm-5 ms-3 font-w500">
                            <svg class="me-1" xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 13 13">
                                <rect  width="13" height="13" fill="#6e6e6e"/>
                            </svg>
                            Expense
                        </span>
                        <span class="fs-16 font-w600">239</span>
                        <div class="tab-content mt-5" id="myTabContent">
                            <div class="tab-pane fade show active" id="monthly1">
                                <canvas id="activity1" class="chartjs"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-12">
                <div class="card">
                    <div class="card-header border-0 pb-0">
                        <h4 class="fs-20 mb-1">Featured Companies</h4>
                        <div class="dropdown">
                            <a href="javascript:void(0);" class="btn-link" data-bs-toggle="dropdown" aria-expanded="false">
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M12 13C12.5523 13 13 12.5523 13 12C13 11.4477 12.5523 11 12 11C11.4477 11 11 11.4477 11 12C11 12.5523 11.4477 13 12 13Z" stroke="#575757" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                                    <path d="M12 6C12.5523 6 13 5.55228 13 5C13 4.44772 12.5523 4 12 4C11.4477 4 11 4.44772 11 5C11 5.55228 11.4477 6 12 6Z" stroke="#575757" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                                    <path d="M12 20C12.5523 20 13 19.5523 13 19C13 18.4477 12.5523 18 12 18C11.4477 18 11 18.4477 11 19C11 19.5523 11.4477 20 12 20Z" stroke="#575757" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                                </svg>
                            </a>
                            <div class="dropdown-menu">
                                <a class="dropdown-item" href="javascript:void(0);">Delete</a>
                                <a class="dropdown-item" href="javascript:void(0);">Edit</a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body pt-3 loadmore-content">
                        <div class="row" id="FeaturedCompaniesContent">
                            <div class="col-xl-6 col-sm-6 mt-4 ">
                                <div class="d-flex">
                                    <span>
                                        <svg xmlns="http://www.w3.org/2000/svg" width="71" height="71" viewBox="0 0 71 71">
                                                <g  transform="translate(-457 -443)">
                                                <rect  width="71" height="71" rx="12" transform="translate(457 443)" fill="#c5c5c5"/>
                                                <g  transform="translate(457 443)">
                                                    <rect  data-name="placeholder" width="71" height="71" rx="12" fill="#2769ee"/>
                                                    <circle  data-name="Ellipse 12" cx="18" cy="18" r="18" transform="translate(15 20)" fill="#fff"/>
                                                    <circle  data-name="Ellipse 11" cx="11" cy="11" r="11" transform="translate(36 15)" fill="#ffe70c" style="mix-blend-mode: multiply;isolation: isolate"/>
                                                </g>
                                                </g>
                                        </svg>
                                    </span>
                                    <div class="ms-3 featured">
                                        <h4 class="fs-20 mb-1">Kleon Team</h4>
                                        <span>Desgin Team Agency</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-6 mt-4 col-sm-6">
                                <div class="d-flex">
                                    <span>
                                        <svg xmlns="http://www.w3.org/2000/svg" width="71" height="71" viewBox="0 0 71 71">
                                                <g  transform="translate(-457 -443)">
                                                <rect  width="71" height="71" rx="12" transform="translate(457 443)" fill="#c5c5c5"/>
                                                <g  transform="translate(457 443)">
                                                    <rect  data-name="placeholder" width="71" height="71" rx="12" fill="#7630d2"/>
                                                    <circle  data-name="Ellipse 12" cx="18" cy="18" r="18" transform="translate(15 20)" fill="#fff"/>
                                                    <circle  data-name="Ellipse 11" cx="11" cy="11" r="11" transform="translate(36 15)" fill="#ffe70c" style="mix-blend-mode: multiply;isolation: isolate"/>
                                                </g>
                                                </g>
                                        </svg>
                                    </span>
                                    <div class="ms-3 featured">
                                        <h4 class="fs-20 mb-1">Ziro Studios Inc.</h4>
                                        <span>Desgin Team Agency</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-6  col-sm-6 mt-4">
                                <div class="d-flex">
                                    <span>
                                        <svg xmlns="http://www.w3.org/2000/svg" width="71" height="71" viewBox="0 0 71 71">
                                                <g  transform="translate(-457 -443)">
                                                <rect  width="71" height="71" rx="12" transform="translate(457 443)" fill="#c5c5c5"/>
                                                <g  transform="translate(457 443)">
                                                    <rect  data-name="placeholder" width="71" height="71" rx="12" fill="#b848ef"/>
                                                    <circle  data-name="Ellipse 12" cx="18" cy="18" r="18" transform="translate(15 20)" fill="#fff"/>
                                                    <circle  data-name="Ellipse 11" cx="11" cy="11" r="11" transform="translate(36 15)" fill="#ffe70c" style="mix-blend-mode: multiply;isolation: isolate"/>
                                                </g>
                                                </g>
                                        </svg>
                                    </span>
                                    <div class="ms-3 featured">
                                        <h4 class="fs-20 mb-1">Qerza</h4>
                                        <span>Desgin Team Agency</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-6 col-sm-6 mt-4">
                                <div class="d-flex">
                                    <span>
                                        <svg xmlns="http://www.w3.org/2000/svg" width="71" height="71" viewBox="0 0 71 71">
                                                <g  transform="translate(-457 -443)">
                                                <rect  width="71" height="71" rx="12" transform="translate(457 443)" fill="#c5c5c5"/>
                                                <g  transform="translate(457 443)">
                                                    <rect  data-name="placeholder" width="71" height="71" rx="12" fill="#7e1d74"/>
                                                    <circle  data-name="Ellipse 12" cx="18" cy="18" r="18" transform="translate(15 20)" fill="#fff"/>
                                                    <circle  data-name="Ellipse 11" cx="11" cy="11" r="11" transform="translate(36 15)" fill="#ffe70c" style="mix-blend-mode: multiply;isolation: isolate"/>
                                                </g>
                                                </g>
                                        </svg>
                                    </span>
                                    <div class="ms-3 featured">
                                        <h4 class="fs-20 mb-1">Kripton Studios</h4>
                                        <span>Desgin Team Agency</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-6 col-sm-6 mt-4">
                                <div class="d-flex">
                                    <span>
                                        <svg xmlns="http://www.w3.org/2000/svg" width="71" height="71" viewBox="0 0 71 71">
                                                <g  transform="translate(-457 -443)">
                                                <rect  width="71" height="71" rx="12" transform="translate(457 443)" fill="#c5c5c5"/>
                                                <g  transform="translate(457 443)">
                                                    <rect  data-name="placeholder" width="71" height="71" rx="12" fill="#0411c2"/>
                                                    <circle  data-name="Ellipse 12" cx="18" cy="18" r="18" transform="translate(15 20)" fill="#fff"/>
                                                    <circle  data-name="Ellipse 11" cx="11" cy="11" r="11" transform="translate(36 15)" fill="#ffe70c" style="mix-blend-mode: multiply;isolation: isolate"/>
                                                </g>
                                                </g>
                                        </svg>
                                    </span>
                                    <div class="ms-3 featured">
                                        <h4 class="fs-20 mb-1">Omah Ku Inc.</h4>
                                        <span>Desgin Team Agency</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-6 col-sm-6 mt-4">
                                <div class="d-flex">
                                    <span>
                                        <svg xmlns="http://www.w3.org/2000/svg" width="71" height="71" viewBox="0 0 71 71">
                                                <g  transform="translate(-457 -443)">
                                                <rect  width="71" height="71" rx="12" transform="translate(457 443)" fill="#c5c5c5"/>
                                                <g  transform="translate(457 443)">
                                                    <rect  data-name="placeholder" width="71" height="71" rx="12" fill="#378a82"/>
                                                    <circle  data-name="Ellipse 12" cx="18" cy="18" r="18" transform="translate(15 20)" fill="#fff"/>
                                                    <circle  data-name="Ellipse 11" cx="11" cy="11" r="11" transform="translate(36 15)" fill="#ffe70c" style="mix-blend-mode: multiply;isolation: isolate"/>
                                                </g>
                                                </g>
                                        </svg>
                                    </span>
                                    <div class="ms-3 featured">
                                        <h4 class="fs-20 mb-1">Ventic</h4>
                                        <span>Desgin Team Agency</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-6 col-sm-6 mt-4">
                                <div class="d-flex">
                                    <span>
                                        <svg xmlns="http://www.w3.org/2000/svg" width="71" height="71" viewBox="0 0 71 71">
                                                <g  transform="translate(-457 -443)">
                                                <rect  width="71" height="71" rx="12" transform="translate(457 443)" fill="#c5c5c5"/>
                                                <g  transform="translate(457 443)">
                                                    <rect  data-name="placeholder" width="71" height="71" rx="12" fill="#175baa"/>
                                                    <circle  data-name="Ellipse 12" cx="18" cy="18" r="18" transform="translate(15 20)" fill="#fff"/>
                                                    <circle  data-name="Ellipse 11" cx="11" cy="11" r="11" transform="translate(36 15)" fill="#ffe70c" style="mix-blend-mode: multiply;isolation: isolate"/>
                                                </g>
                                                </g>
                                        </svg>
                                    </span>
                                    <div class="ms-3 featured">
                                        <h4 class="fs-20 mb-1">Sakola</h4>
                                        <span>Desgin Team Agency</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-6 col-sm-6 mt-4">
                                <div class="d-flex">
                                    <span>
                                        <svg xmlns="http://www.w3.org/2000/svg" width="71" height="71" viewBox="0 0 71 71">
                                            <g  transform="translate(-457 -443)">
                                            <rect  width="71" height="71" rx="12" transform="translate(457 443)" fill="#c5c5c5"/>
                                            <g  transform="translate(457 443)">
                                                <rect  data-name="placeholder" width="71" height="71" rx="12" fill="#eeb927"/>
                                                <circle  data-name="Ellipse 12" cx="18" cy="18" r="18" transform="translate(15 20)" fill="#fff"/>
                                                <circle  data-name="Ellipse 11" cx="11" cy="11" r="11" transform="translate(36 15)" fill="#ffe70c" style="mix-blend-mode: multiply;isolation: isolate"/>
                                            </g>
                                            </g>
                                        </svg>
                                    </span>
                                    <div class="ms-3 featured">
                                        <h4 class="fs-20 mb-1">Uena Foods</h4>
                                        <span>Desgin Team Agency</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer border-0 m-auto pt-0">
                        <a href="javascript:void(0);" class="btn btn-outline-primary btn-rounded m-auto dlab-load-more" id="FeaturedCompanies" rel="ajax/featuredcompanies.html">View more</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-6">
        <div class="row">
            <div class="col-xl-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row ">
                            <div class="col-xl-8 col-xxl-7 col-sm-7">
                                <div class="update-profile d-flex">
                                    <img src="img/avatar.jpg" alt="">
                                    <div class="ms-4">
                                        <h3 class="fs-24 font-w600 mb-0">Franklin Jr</h3>
                                        <span class="text-primary d-block mb-4">UI / UX Designer</span>
                                        <span><i class="fas fa-map-marker-alt me-1"></i>Medan, Sumatera Utara - ID</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-4 col-xxl-5 col-sm-5 sm-mt-auto mt-3">
                                <a href="javascript:void(0);" class="btn btn-primary btn-rounded">Update Profile</a>
                            </div>
                        </div>
                        <div class="row mt-4 align-items-center">
                            <h4 class="fs-20 mb-3">Skills</h4>
                            <div class="col-xl-6 col-sm-6">
                                <div class="progress default-progress">
                                    <div class="progress-bar bg-green progress-animated" style="width: 90%; height:13px;" role="progressbar">
                                        <span class="sr-only">90% Complete</span>
                                    </div>
                                </div>
                                <div class="d-flex align-items-end mt-2 pb-4 justify-content-between">
                                    <span class="fs-14 font-w500">Figma</span>
                                    <span class="fs-16"><span class="text-black pe-2"></span>90%</span>
                                </div>
                                <div class="progress default-progress">
                                    <div class="progress-bar bg-info progress-animated" style="width: 68%; height:13px;" role="progressbar">
                                        <span class="sr-only">45% Complete</span>
                                    </div>
                                </div>
                                <div class="d-flex align-items-end mt-2 pb-4 justify-content-between">
                                    <span class="fs-14 font-w500">Adobe XD</span>
                                    <span class="fs-16"><span class="text-black pe-2"></span>68%</span>
                                </div>
                                <div class="progress default-progress">
                                    <div class="progress-bar bg-blue progress-animated" style="width: 85%; height:13px;" role="progressbar">
                                        <span class="sr-only">85% Complete</span>
                                    </div>
                                </div>
                                <div class="d-flex align-items-end mt-2 pb-4 justify-content-between">
                                    <span class="fs-14 font-w500">Photoshop</span>
                                    <span class="fs-16"><span class="text-black pe-2"></span>85%</span>
                                </div>
                            </div>
                            <div class="col-xl-6 col-sm-6">
                                <div id="pieChart1"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-12">
                <div class="card">
                    <div class="card-header border-0">
                        <h4 class="fs-20 mb-3">Recent Activity</h4>
                        <div>	
                            <select class="default-select dashboard-select">
                                <option data-display="newest">newest</option>
                                
                                <option value="2">oldest</option>
                            </select>
                            <div class="dropdown custom-dropdown mb-0">
                                <div class="btn sharp tp-btn dark-btn" data-bs-toggle="dropdown">
                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M12 13C12.5523 13 13 12.5523 13 12C13 11.4477 12.5523 11 12 11C11.4477 11 11 11.4477 11 12C11 12.5523 11.4477 13 12 13Z" stroke="#342E59" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                        <path d="M12 6C12.5523 6 13 5.55228 13 5C13 4.44772 12.5523 4 12 4C11.4477 4 11 4.44772 11 5C11 5.55228 11.4477 6 12 6Z" stroke="#342E59" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                        <path d="M12 20C12.5523 20 13 19.5523 13 19C13 18.4477 12.5523 18 12 18C11.4477 18 11 18.4477 11 19C11 19.5523 11.4477 20 12 20Z" stroke="#342E59" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                    </svg>
                                </div>
                                <div class="dropdown-menu dropdown-menu-right">
                                    <a class="dropdown-item" href="javascript:void(0);">Details</a>
                                    <a class="dropdown-item text-danger" href="javascript:void(0);">Cancel</a>
                                </div>
                            </div>
                        </div>	
                    </div>
                    <div class="card-body loadmore-content  recent-activity-wrapper" id="RecentActivityContent">
                        <div class="d-flex recent-activity">
                            <span class="me-3 activity">
                                <svg xmlns="http://www.w3.org/2000/svg" width="17" height="17" viewBox="0 0 17 17">
                                    <circle  cx="8.5" cy="8.5" r="8.5" fill="#f93a0b"/>
                                </svg>
                            </span>
                            <span>
                                <svg xmlns="http://www.w3.org/2000/svg" width="71" height="71" viewBox="0 0 71 71">
                                    <g  transform="translate(-457 -443)">
                                    <rect  width="71" height="71" rx="12" transform="translate(457 443)" fill="#c5c5c5"/>
                                    <g  transform="translate(457 443)">
                                        <rect  data-name="placeholder" width="71" height="71" rx="12" fill="#2769ee"/>
                                        <circle  data-name="Ellipse 12" cx="18" cy="18" r="18" transform="translate(15 20)" fill="#fff"/>
                                        <circle  data-name="Ellipse 11" cx="11" cy="11" r="11" transform="translate(36 15)" fill="#ffe70c" style="mix-blend-mode: multiply;isolation: isolate"/>
                                    </g>
                                    </g>
                                </svg>
                            </span>
                            <div class="ms-3">
                                <h5 class="mb-1">Bubles Studios have 5 available positions for you</h5>
                                <span>8min ago</span>
                            </div>
                        </div>
                        <div class="d-flex recent-activity">
                            <span class="me-3 activity">
                                <svg xmlns="http://www.w3.org/2000/svg" width="17" height="17" viewBox="0 0 17 17">
                                    <circle  cx="8.5" cy="8.5" r="8.5" fill="#a1a1a1"/>
                                </svg>
                            </span>
                            <span>
                                <svg xmlns="http://www.w3.org/2000/svg" width="71" height="71" viewBox="0 0 71 71">
                                        <g  transform="translate(-457 -443)">
                                        <rect  width="71" height="71" rx="12" transform="translate(457 443)" fill="#c5c5c5"/>
                                        <g  transform="translate(457 443)">
                                            <rect  data-name="placeholder" width="71" height="71" rx="12" fill="#eeac27"/>
                                            <circle  data-name="Ellipse 12" cx="18" cy="18" r="18" transform="translate(15 20)" fill="#fff"/>
                                            <circle  data-name="Ellipse 11" cx="11" cy="11" r="11" transform="translate(36 15)" fill="#ffe70c" style="mix-blend-mode: multiply;isolation: isolate"/>
                                        </g>
                                        </g>
                                </svg>
                            </span>
                            <div class="ms-3">
                                <h5 class="mb-1">Elextra Studios has invited you to interview meeting tomorrow</h5>
                                <span>8min ago</span>
                            </div>
                        </div>
                        <div class="d-flex recent-activity">
                            <span class="me-3 activity">
                                <svg xmlns="http://www.w3.org/2000/svg" width="17" height="17" viewBox="0 0 17 17">
                                    <circle  cx="8.5" cy="8.5" r="8.5" fill="#a1a1a1"/>
                                </svg>
                            </span>
                            <span>
                                <svg xmlns="http://www.w3.org/2000/svg" width="71" height="71" viewBox="0 0 71 71">
                                        <g  transform="translate(-457 -443)">
                                        <rect  width="71" height="71" rx="12" transform="translate(457 443)" fill="#c5c5c5"/>
                                        <g  transform="translate(457 443)">
                                            <rect  data-name="placeholder" width="71" height="71" rx="12" fill="#22bc32"/>
                                            <circle  data-name="Ellipse 12" cx="18" cy="18" r="18" transform="translate(15 20)" fill="#fff"/>
                                            <circle  data-name="Ellipse 11" cx="11" cy="11" r="11" transform="translate(36 15)" fill="#ffe70c" style="mix-blend-mode: multiply;isolation: isolate"/>
                                        </g>
                                        </g>
                                </svg>
                            </span>
                            <div class="ms-3">
                                <h5 class="mb-1">Highspeed Design Team have 2 available positions for you</h5>
                                <span>8min ago</span>
                            </div>
                        </div>
                        <div class="d-flex recent-activity">
                            <span class="me-3">
                                <svg xmlns="http://www.w3.org/2000/svg" width="17" height="17" viewBox="0 0 17 17">
                                    <circle  cx="8.5" cy="8.5" r="8.5" fill="#a1a1a1"/>
                                </svg>
                            </span>
                            <span>
                                <svg xmlns="http://www.w3.org/2000/svg" width="71" height="71" viewBox="0 0 71 71">
                                        <g  transform="translate(-457 -443)">
                                        <rect  width="71" height="71" rx="12" transform="translate(457 443)" fill="#c5c5c5"/>
                                        <g  transform="translate(457 443)">
                                            <rect  data-name="placeholder" width="71" height="71" rx="12" fill="#9933cb"/>
                                            <circle  data-name="Ellipse 12" cx="18" cy="18" r="18" transform="translate(15 20)" fill="#fff"/>
                                            <circle  data-name="Ellipse 11" cx="11" cy="11" r="11" transform="translate(36 15)" fill="#ffe70c" style="mix-blend-mode: multiply;isolation: isolate"/>
                                        </g>
                                        </g>
                                </svg>
                            </span>
                            <div class="ms-3">
                                <h5 class="mb-1">Kleon Studios have 5 available positions for you</h5>
                                <span>8min ago</span>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer border-0 m-auto pt-0">
                        <a href="javascript:void(0);" class="btn btn-outline-primary btn-rounded m-auto dlab-load-more" id="RecentActivity" rel="ajax/recentactivity.html">View more</a>
                    </div>
                </div>
            </div>
            <div class="col-xl-12">
                <div class="card">
                    <div class="card-header pb-0 border-0 flex-wrap">
                        <h4 class="fs-20 mb-3">Available Jobs For You</h4>
                        <div>	
                            <select class="default-select dashboard-select">
                                <option data-display="newest">newest</option>
                                
                                <option value="2">oldest</option>
                            </select>
                            <div class="dropdown custom-dropdown mb-0">
                                <div class="btn sharp tp-btn dark-btn" data-bs-toggle="dropdown">
                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M12 13C12.5523 13 13 12.5523 13 12C13 11.4477 12.5523 11 12 11C11.4477 11 11 11.4477 11 12C11 12.5523 11.4477 13 12 13Z" stroke="#342E59" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                        <path d="M12 6C12.5523 6 13 5.55228 13 5C13 4.44772 12.5523 4 12 4C11.4477 4 11 4.44772 11 5C11 5.55228 11.4477 6 12 6Z" stroke="#342E59" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                        <path d="M12 20C12.5523 20 13 19.5523 13 19C13 18.4477 12.5523 18 12 18C11.4477 18 11 18.4477 11 19C11 19.5523 11.4477 20 12 20Z" stroke="#342E59" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                    </svg>
                                </div>
                                <div class="dropdown-menu dropdown-menu-right">
                                    <a class="dropdown-item" href="javascript:void(0);">Details</a>
                                    <a class="dropdown-item text-danger" href="javascript:void(0);">Cancel</a>
                                </div>
                            </div>
                        </div>	
                    </div>
                    <div class="card-body">
                        <div class="owl-carousel owl-carousel owl-loaded front-view-slider ">
                            <div class="items">
                                <div class="jobs">
                                    <div class="text-center">
                                        <span>
                                            <svg class="mb-2" xmlns="http://www.w3.org/2000/svg" width="71" height="71" viewBox="0 0 71 71">
                                                <g  transform="translate(-457 -443)">
                                                <rect  width="71" height="71" rx="12" transform="translate(457 443)" fill="#c5c5c5"/>
                                                <g  transform="translate(457 443)">
                                                    <rect  data-name="placeholder" width="71" height="71" rx="12" fill="#2769ee"/>
                                                    <circle  data-name="Ellipse 12" cx="18" cy="18" r="18" transform="translate(15 20)" fill="#fff"/>
                                                    <circle  data-name="Ellipse 11" cx="11" cy="11" r="11" transform="translate(36 15)" fill="#ffe70c" style="mix-blend-mode: multiply;isolation: isolate"/>
                                                </g>
                                                </g>
                                            </svg>
                                        </span>
                                        <h4 class="mb-0">UI Designer</h4>
                                        <span class="text-primary mb-3 d-block">Bubbles Studios</span>
                                    </div>
                                    <div>
                                    <span class="d-block mb-1"><i class="fas fa-map-marker-alt me-2"></i>Manchester, England</span>
                                        <span><i class="fas fa-comments-dollar me-2"></i>$ 2,000 - $ 2,500</span>
                                    </div>
                                </div>	
                            </div>
                            <div class="items">
                                <div class="jobs">
                                    <div class="text-center">
                                        <span>
                                            <svg class="mb-2" xmlns="http://www.w3.org/2000/svg" width="71" height="71" viewBox="0 0 71 71">
                                                <g  transform="translate(-457 -443)">
                                                <rect  width="71" height="71" rx="12" transform="translate(457 443)" fill="#c5c5c5"/>
                                                <g  transform="translate(457 443)">
                                                    <rect  data-name="placeholder" width="71" height="71" rx="12" fill="#ee27c0"/>
                                                    <circle  data-name="Ellipse 12" cx="18" cy="18" r="18" transform="translate(15 20)" fill="#fff"/>
                                                    <circle  data-name="Ellipse 11" cx="11" cy="11" r="11" transform="translate(36 15)" fill="#ffe70c" style="mix-blend-mode: multiply;isolation: isolate"/>
                                                </g>
                                                </g>
                                            </svg>
                                        </span>
                                        <h4 class="mb-0">Researcher</h4>
                                        <span class="text-primary mb-3 d-block">Kleon Studios</span>
                                    </div>
                                    <div>
                                    <span class="d-block mb-1"><i class="fas fa-map-marker-alt me-2"></i>Manchester, England</span>
                                        <span><i class="fas fa-comments-dollar me-2"></i>$ 2,000 - $ 2,500</span>
                                    </div>
                                </div>	
                            </div>
                            <div class="items">
                                <div class="jobs">
                                    <div class="text-center">
                                        <span>
                                            <svg class="mb-2" xmlns="http://www.w3.org/2000/svg" width="71" height="71" viewBox="0 0 71 71">
                                                <g  transform="translate(-457 -443)">
                                                <rect  width="71" height="71" rx="12" transform="translate(457 443)" fill="#c5c5c5"/>
                                                <g  transform="translate(457 443)">
                                                    <rect  data-name="placeholder" width="71" height="71" rx="12" fill="#2db532"/>
                                                    <circle  data-name="Ellipse 12" cx="18" cy="18" r="18" transform="translate(15 20)" fill="#fff"/>
                                                    <circle  data-name="Ellipse 11" cx="11" cy="11" r="11" transform="translate(36 15)" fill="#ffe70c" style="mix-blend-mode: multiply;isolation: isolate"/>
                                                </g>
                                                </g>
                                            </svg>
                                        </span>
                                        <h4 class="mb-0">Frontend</h4>
                                        <span class="text-primary mb-3 d-block">Green Comp.</span>
                                    </div>
                                    <div>
                                    <span class="d-block mb-1"><i class="fas fa-map-marker-alt me-2"></i>Manchester, England</span>
                                        <span><i class="fas fa-comments-dollar me-2"></i>$ 2,000 - $ 2,500</span>
                                    </div>
                                </div>	
                            </div>
                            <div class="items">
                                <div class="jobs">
                                    <div class="text-center">
                                        <span>
                                            <svg class="mb-2" xmlns="http://www.w3.org/2000/svg" width="71" height="71" viewBox="0 0 71 71">
                                                <g  transform="translate(-457 -443)">
                                                <rect  width="71" height="71" rx="12" transform="translate(457 443)" fill="#c5c5c5"/>
                                                <g  transform="translate(457 443)">
                                                    <rect  data-name="placeholder" width="71" height="71" rx="12" fill="#2769ee"/>
                                                    <circle  data-name="Ellipse 12" cx="18" cy="18" r="18" transform="translate(15 20)" fill="#fff"/>
                                                    <circle  data-name="Ellipse 11" cx="11" cy="11" r="11" transform="translate(36 15)" fill="#ffe70c" style="mix-blend-mode: multiply;isolation: isolate"/>
                                                </g>
                                                </g>
                                            </svg>
                                        </span>
                                        <h4 class="mb-0">UI Designer</h4>
                                        <span class="text-primary mb-3 d-block">Bubbles Studios</span>
                                    </div>
                                    <div>
                                    <span class="d-block mb-1"><i class="fas fa-map-marker-alt me-2"></i>Manchester, England</span>
                                        <span><i class="fas fa-comments-dollar me-2"></i>$ 2,000 - $ 2,500</span>
                                    </div>
                                </div>	
                            </div>
                            <div class="items">
                                <div class="jobs">
                                    <div class="text-center">
                                        <span>
                                            <svg class="mb-2" xmlns="http://www.w3.org/2000/svg" width="71" height="71" viewBox="0 0 71 71">
                                                <g  transform="translate(-457 -443)">
                                                <rect  width="71" height="71" rx="12" transform="translate(457 443)" fill="#c5c5c5"/>
                                                <g  transform="translate(457 443)">
                                                    <rect  data-name="placeholder" width="71" height="71" rx="12" fill="#ee27c0"/>
                                                    <circle  data-name="Ellipse 12" cx="18" cy="18" r="18" transform="translate(15 20)" fill="#fff"/>
                                                    <circle  data-name="Ellipse 11" cx="11" cy="11" r="11" transform="translate(36 15)" fill="#ffe70c" style="mix-blend-mode: multiply;isolation: isolate"/>
                                                </g>
                                                </g>
                                            </svg>
                                        </span>
                                        <h4 class="mb-0">Researcher</h4>
                                        <span class="text-primary mb-3 d-block">Kleon Studios</span>
                                    </div>
                                    <div>
                                    <span class="d-block mb-1"><i class="fas fa-map-marker-alt me-2"></i>Manchester, England</span>
                                        <span><i class="fas fa-comments-dollar me-2"></i>$ 2,000 - $ 2,500</span>
                                    </div>
                                </div>	
                            </div>
                            <div class="items">
                                <div class="jobs">
                                    <div class="text-center">
                                        <span>
                                            <svg class="mb-2" xmlns="http://www.w3.org/2000/svg" width="71" height="71" viewBox="0 0 71 71">
                                                <g  transform="translate(-457 -443)">
                                                <rect  width="71" height="71" rx="12" transform="translate(457 443)" fill="#c5c5c5"/>
                                                <g  transform="translate(457 443)">
                                                    <rect  data-name="placeholder" width="71" height="71" rx="12" fill="#2db532"/>
                                                    <circle  data-name="Ellipse 12" cx="18" cy="18" r="18" transform="translate(15 20)" fill="#fff"/>
                                                    <circle  data-name="Ellipse 11" cx="11" cy="11" r="11" transform="translate(36 15)" fill="#ffe70c" style="mix-blend-mode: multiply;isolation: isolate"/>
                                                </g>
                                                </g>
                                            </svg>
                                        </span>
                                        <h4 class="mb-0">Frontend</h4>
                                        <span class="text-primary mb-3 d-block">Green Comp.</span>
                                    </div>
                                    <div>
                                    <span class="d-block mb-1"><i class="fas fa-map-marker-alt me-2"></i>Manchester, England</span>
                                        <span><i class="fas fa-comments-dollar me-2"></i>$ 2,000 - $ 2,500</span>
                                    </div>
                                </div>	
                            </div>
                        </div>
                    </div>
                </div>
                
            </div>
        </div>
    </div>
    
</div>	

<?php

$this->registerJsFile("librerias/chart.js/Chart.bundle.min.js");
$this->registerJsFile("librerias/apexchart/apexchart.js");
$this->registerJsFile("librerias/owl-carousel/owl.carousel.js");

$js = <<<JS

(function($) {

    $(window).on('load',function(){
		setTimeout(function(){
			$('.front-view-slider').owlCarousel({
				loop: false,
				margin: 30,
				nav: true,
				autoplaySpeed: 3000,
				navSpeed: 3000,
				autoWidth: true,
				paginationSpeed: 3000,
				slideSpeed: 3000,
				smartSpeed: 3000,
				autoplay: false,
				animateOut: 'fadeOut',
				dots: true,
				navText: ['', ''],
				responsive: {
					0: { items: 1 },
					480: { items: 1 },			
					767: { items: 3 },
					1750: { items: 3 }
				}
			});
		}, 1000); 
	});

    var dlabChartlist = function(){
	
	var screenWidth = $(window).width();
	let draw = Chart.controllers.line.__super__.draw; //draw shadow
	
	var NewCustomers = function(){
		var options = {
		  series: [
			{
				name: 'Net Profit',
				data: [100,300, 100, 400, 200, 400],
				/* radius: 30,	 */
			}, 				
		],
			chart: {
			type: 'line',
			height: 50,
			width: 100,
			toolbar: {
				show: false,
			},
			zoom: {
				enabled: false
			},
			sparkline: {
				enabled: true
			}
			
		},
		
		colors:['var(--primary)'],
		dataLabels: {
		  enabled: false,
		},

		legend: {
			show: false,
		},
		stroke: {
		  show: true,
		  width: 6,
		  curve:'smooth',
		  colors:['var(--primary)'],
		},
		
		grid: {
			show:false,
			borderColor: '#eee',
			padding: {
				top: 0,
				right: 0,
				bottom: 0,
				left: 0

			}
		},
		states: {
                normal: {
                    filter: {
                        type: 'none',
                        value: 0
                    }
                },
                hover: {
                    filter: {
                        type: 'none',
                        value: 0
                    }
                },
                active: {
                    allowMultipleDataPointsSelection: false,
                    filter: {
                        type: 'none',
                        value: 0
                    }
                }
            },
		xaxis: {
			categories: ['Jan', 'feb', 'Mar', 'Apr', 'May'],
			axisBorder: {
				show: false,
			},
			axisTicks: {
				show: false
			},
			labels: {
				show: false,
				style: {
					fontSize: '12px',
				}
			},
			crosshairs: {
				show: false,
				position: 'front',
				stroke: {
					width: 1,
					dashArray: 3
				}
			},
			tooltip: {
				enabled: true,
				formatter: undefined,
				offsetY: 0,
				style: {
					fontSize: '12px',
				}
			}
		},
		yaxis: {
			show: false,
		},
		fill: {
		  opacity: 1,
		  colors:'#FB3E7A'
		},
		tooltip: {
			enabled:false,
			style: {
				fontSize: '12px',
			},
			y: {
				formatter: function(val) {
					return "$" + val + " thousands"
				}
			}
		}
		};

		var chartBar1 = new ApexCharts(document.querySelector("#NewCustomers"), options);
		chartBar1.render();
	 
	}
	var NewCustomers1 = function(){
		var options = {
		  series: [
			{
				name: 'Net Profit',
				data: [100,300, 200, 400, 100, 400],
				/* radius: 30,	 */
			}, 				
		],
			chart: {
			type: 'line',
			height: 50,
			width: 80,
			toolbar: {
				show: false,
			},
			zoom: {
				enabled: false
			},
			sparkline: {
				enabled: true
			}
			
		},
		
		colors:['#0E8A74'],
		dataLabels: {
		  enabled: false,
		},

		legend: {
			show: false,
		},
		stroke: {
		  show: true,
		  width: 6,
		  curve:'smooth',
		  colors:['#145650'],
		},
		
		grid: {
			show:false,
			borderColor: '#eee',
			padding: {
				top: 0,
				right: 0,
				bottom: 0,
				left: 0

			}
		},
		states: {
                normal: {
                    filter: {
                        type: 'none',
                        value: 0
                    }
                },
                hover: {
                    filter: {
                        type: 'none',
                        value: 0
                    }
                },
                active: {
                    allowMultipleDataPointsSelection: false,
                    filter: {
                        type: 'none',
                        value: 0
                    }
                }
            },
		xaxis: {
			categories: ['Jan', 'feb', 'Mar', 'Apr', 'May'],
			axisBorder: {
				show: false,
			},
			axisTicks: {
				show: false
			},
			labels: {
				show: false,
				style: {
					fontSize: '12px',
				}
			},
			crosshairs: {
				show: false,
				position: 'front',
				stroke: {
					width: 1,
					dashArray: 3
				}
			},
			tooltip: {
				enabled: true,
				formatter: undefined,
				offsetY: 0,
				style: {
					fontSize: '12px',
				}
			}
		},
		yaxis: {
			show: false,
		},
		fill: {
		  opacity: 1,
		  colors:'#FB3E7A'
		},
		tooltip: {
			enabled:false,
			style: {
				fontSize: '12px',
			},
			y: {
				formatter: function(val) {
					return "$" + val + " thousands"
				}
			}
		}
		};

		var chartBar1 = new ApexCharts(document.querySelector("#NewCustomers1"), options);
		chartBar1.render();
	 
	}
	var NewCustomers2 = function(){
		var options = {
		  series: [
			{
				name: 'Net Profit',
				data: [100,200, 100, 300, 200, 400],
				/* radius: 30,	 */
			}, 				
		],
			chart: {
			type: 'line',
			height: 50,
			width: 80,
			toolbar: {
				show: false,
			},
			zoom: {
				enabled: false
			},
			sparkline: {
				enabled: true
			}
			
		},
		
		colors:['#0E8A74'],
		dataLabels: {
		  enabled: false,
		},

		legend: {
			show: false,
		},
		stroke: {
		  show: true,
		  width: 6,
		  curve:'smooth',
		  colors:['#3385D6'],
		},
		
		grid: {
			show:false,
			borderColor: '#eee',
			padding: {
				top: 0,
				right: 0,
				bottom: 0,
				left: 0

			}
		},
		states: {
                normal: {
                    filter: {
                        type: 'none',
                        value: 0
                    }
                },
                hover: {
                    filter: {
                        type: 'none',
                        value: 0
                    }
                },
                active: {
                    allowMultipleDataPointsSelection: false,
                    filter: {
                        type: 'none',
                        value: 0
                    }
                }
            },
		xaxis: {
			categories: ['Jan', 'feb', 'Mar', 'Apr', 'May'],
			axisBorder: {
				show: false,
			},
			axisTicks: {
				show: false
			},
			labels: {
				show: false,
				style: {
					fontSize: '12px',
				}
			},
			crosshairs: {
				show: false,
				position: 'front',
				stroke: {
					width: 1,
					dashArray: 3
				}
			},
			tooltip: {
				enabled: true,
				formatter: undefined,
				offsetY: 0,
				style: {
					fontSize: '12px',
				}
			}
		},
		yaxis: {
			show: false,
		},
		fill: {
		  opacity: 1,
		  colors:'#FB3E7A'
		},
		tooltip: {
			enabled:false,
			style: {
				fontSize: '12px',
			},
			y: {
				formatter: function(val) {
					return "$" + val + " thousands"
				}
			}
		}
		};

		var chartBar1 = new ApexCharts(document.querySelector("#NewCustomers2"), options);
		chartBar1.render();
	 
	}
	var NewCustomers3 = function(){
		var options = {
		  series: [
			{
				name: 'Net Profit',
				data: [100,200, 100, 300, 200, 400],
				/* radius: 30,	 */
			}, 				
		],
			chart: {
			type: 'line',
			height: 50,
			width: 100,
			toolbar: {
				show: false,
			},
			zoom: {
				enabled: false
			},
			sparkline: {
				enabled: true
			}
			
		},
		
		colors:['#0E8A74'],
		dataLabels: {
		  enabled: false,
		},

		legend: {
			show: false,
		},
		stroke: {
		  show: true,
		  width: 6,
		  curve:'smooth',
		  colors:['#B723AD'],
		},
		
		grid: {
			show:false,
			borderColor: '#eee',
			padding: {
				top: 0,
				right: 0,
				bottom: 0,
				left: 0

			}
		},
		states: {
                normal: {
                    filter: {
                        type: 'none',
                        value: 0
                    }
                },
                hover: {
                    filter: {
                        type: 'none',
                        value: 0
                    }
                },
                active: {
                    allowMultipleDataPointsSelection: false,
                    filter: {
                        type: 'none',
                        value: 0
                    }
                }
            },
		xaxis: {
			categories: ['Jan', 'feb', 'Mar', 'Apr', 'May'],
			axisBorder: {
				show: false,
			},
			axisTicks: {
				show: false
			},
			labels: {
				show: false,
				style: {
					fontSize: '12px',
				}
			},
			crosshairs: {
				show: false,
				position: 'front',
				stroke: {
					width: 1,
					dashArray: 3
				}
			},
			tooltip: {
				enabled: true,
				formatter: undefined,
				offsetY: 0,
				style: {
					fontSize: '12px',
				}
			}
		},
		yaxis: {
			show: false,
		},
		fill: {
		  opacity: 1,
		  colors:'#FB3E7A'
		},
		tooltip: {
			enabled:false,
			style: {
				fontSize: '12px',
			},
			y: {
				formatter: function(val) {
					return "$" + val + " thousands"
				}
			}
		}
		};

		var chartBar1 = new ApexCharts(document.querySelector("#NewCustomers3"), options);
		chartBar1.render();
	 
	}
	var activityChart = function(){
		var activity = document.getElementById("activity");
			if (activity !== null) {
				var activityData = [{
						first: [ 30, 35, 30, 50, 30, 50, 40, 45],
						second: [ 20, 25, 20, 15, 25, 22, 24, 20],
						third: [ 20, 21, 22, 21, 22, 15, 17, 20]
					},
					{
						first: [ 35, 35, 40, 30, 38, 40, 50, 38],
						second: [ 30, 20, 35, 20, 25, 30, 25, 20],
						third: [ 35, 40, 40, 30, 38, 50, 42, 32]
					},
					{
						first: [ 35, 40, 40, 30, 38, 32, 42, 32],
						second: [ 20, 25, 35, 25, 22, 21, 21, 38],
						third: [ 30, 35, 40, 30, 38, 50, 42, 32]
					},
					{
						first: [ 35, 40, 30, 38, 32, 42, 30, 35],
						second: [ 25, 30, 35, 25, 20, 22, 25, 38],
						third: [ 35, 35, 40, 30, 38, 40, 30, 38]
					}
				];
				activity.height = 300;
				
				var config = {
					type: "line",
					data: {
						labels: [
							"Mar",
							"Apr",
							"May",
							"June",
							"Jul",
							"Aug",
							"Sep",
							"Oct",
						],
						datasets: [{
								label: "Active",
								backgroundColor: "rgba(82, 177, 65, 0)",
								borderColor: "#3FC55E",
								data: activityData[0].first,
								borderWidth: 6
							},
							{
								label: "Inactive",
								backgroundColor: 'rgba(255, 142, 38, 0)',
								borderColor: "#4955FA",
								data: activityData[0].second,
								borderWidth: 6,
								
							},
							{
								label: "Inactive",
								backgroundColor: 'rgba(255, 142, 38, 0)',
								borderColor: "#F04949",
								data: activityData[0].third,
								borderWidth: 6,
								
							} 
						]
					},
					options: {
						responsive: true,
						maintainAspectRatio: false,
						elements: {
								point:{
									radius: 0
								}
						},
						legend:false,
						
						scales: {
							yAxes: [{
								gridLines: {
									color: "rgba(89, 59, 219,0.1)",
									drawBorder: true
								},
								ticks: {
									fontSize: 14,
									fontColor: "#6E6E6E",
									fontFamily: "Poppins"
								},
							}],
							xAxes: [{
								//FontSize: 40,
								gridLines: {
									display: false,
									zeroLineColor: "transparent"
								},
								ticks: {
									fontSize: 14,
									stepSize: 5,
									fontColor: "#6E6E6E",
									fontFamily: "Poppins"
								}
							}]
						},
						tooltips: {
							enabled: false,
							mode: "index",
							intersect: false,
							titleFontColor: "#888",
							bodyFontColor: "#555",
							titleFontSize: 12,
							bodyFontSize: 15,
							backgroundColor: "rgba(256,256,256,0.95)",
							displayColors: true,
							xPadding: 10,
							yPadding: 7,
							borderColor: "rgba(220, 220, 220, 0.9)",
							borderWidth: 2,
							caretSize: 6,
							caretPadding: 10
						}
					}
				};

				var ctx = document.getElementById("activity").getContext("2d");
				var myLine = new Chart(ctx, config);

				var items = document.querySelectorAll("#user-activity .nav-tabs .nav-item");
				items.forEach(function(item, index) {
					item.addEventListener("click", function() {
						config.data.datasets[0].data = activityData[index].first;
						config.data.datasets[1].data = activityData[index].second;
						config.data.datasets[2].data = activityData[index].third;
						myLine.update();
					});
				});
			}
	
		
	}
	var activityBar1 = function(){
		var activity1 = document.getElementById("activity1");
		if (activity1 !== null) {
			var activity1Data = [{
					first: [35, 18, 15, 35, 40, 20, 30, 25, 22, 20, 45, 35, 35]
				},
				{
					first: [50, 35, 10, 45, 40, 50, 60, 35, 10, 50, 34, 35, 50]
				},
				{
					first: [20, 35, 60, 45, 40, 35, 30, 35, 10, 40, 60, 20, 20]
				},
				{
					first: [25, 88, 25, 50, 21, 42, 60, 33, 50, 60, 50, 20, 25]
				}
			];
			activity1.height = 200;
			
			var config = {
				type: "bar",
				data: {
					labels: [
						"01",
						"02",
						"03",
						"04",
						"05",
						"06",
						"07",
						"08",
						"09",
						"10",
						"11",
						"12",
						"13"
					],
					datasets: [
						{
							label: "My First dataset",
							data:  [35, 18, 15, 35, 40, 20, 30, 25, 22, 20, 45, 35, 35],
							borderColor: 'var(--primary)',
							borderWidth: "0",
							barThickness:'flex',
							backgroundColor: '#F73A0B',
							minBarLength:10
						}
					]
				},
				options: {
					responsive: true,
					maintainAspectRatio: false,
					
					legend: {
						display: false
					},
					scales: {
						yAxes: [{
							gridLines: {
								color: "rgba(233,236,255,1)",
								drawBorder: true
							},
							ticks: {
								fontColor: "#6E6E6E",
								 max: 60,
                min: 0,
                stepSize: 20
							},
						}],
						xAxes: [{
							barPercentage: 0.3,
							
							gridLines: {
								display: false,
								zeroLineColor: "transparent"
							},
							ticks: {
								stepSize: 20,
								fontColor: "#6E6E6E",
								fontFamily: "Nunito, sans-serif"
							}
						}]
					},
					tooltips: {
						mode: "index",
						intersect: false,
						titleFontColor: "#888",
						bodyFontColor: "#555",
						titleFontSize: 12,
						bodyFontSize: 15,
						backgroundColor: "rgba(255,255,255,1)",
						displayColors: true,
						xPadding: 10,
						yPadding: 7,
						borderColor: "rgba(220, 220, 220, 1)",
						borderWidth: 1,
						caretSize: 6,
						caretPadding: 10
					}
				}
			};

			var ctx = document.getElementById("activity1").getContext("2d");
			var myLine = new Chart(ctx, config);

			var items = document.querySelectorAll("#user-activity1 .nav-tabs .nav-item");
			items.forEach(function(item, index) {
				item.addEventListener("click", function() {
					config.data.datasets[0].data = activity1Data[index].first;
					myLine.update();
				});
			});
		}
	}
	var pieChart1 = function(){
		 var options = {
          series: [90, 68, 85],
          chart: {
          type: 'donut',
		  height:250
        },
		dataLabels:{
			enabled: false
		},
		stroke: {
          width: 0,
        },
		colors:['#1D92DF', '#4754CB', '#D55BC1'],
		legend: {
              position: 'bottom',
			  show:false
            },
        responsive: [{
          breakpoint: 1400,
          options: {
           chart: {
			  height:200
			},
          }
        }]
        };

        var chart = new ApexCharts(document.querySelector("#pieChart1"), options);
        chart.render();
    
	}
	
	/* Function ============ */
		return {
			init:function(){
			},
			load:function(){
				NewCustomers();
				NewCustomers1();
				NewCustomers2();
				NewCustomers3();
				activityChart();
				activityBar1();
				pieChart1();
				
			},
			
			resize:function(){
			}
		}
	}();
		
	jQuery(window).on('load',function(){
		setTimeout(function(){
			dlabChartlist.load();
		}, 1000); 
	});

})(jQuery);

JS;

$this->registerJs($script);