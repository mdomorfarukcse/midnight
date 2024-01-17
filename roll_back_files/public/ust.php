  <div class="overlay"></div>
        <div class="search-overlay"></div>

        <!--  BEGIN SIDEBAR  -->
        <div class="sidebar-wrapper sidebar-theme">

            <nav id="sidebar">
              <ul class="navbar-nav theme-brand flex-row  text-center">
                    <li class="nav-item theme-text">
                        <a href="/panel" class="nav-link">   <img style="height: 38px;" src="<?=$setting->getsiteUrl();?>/assets/img/<?=$setting->getsiteLogo();?>" class="navbar-logo" alt="logo"> </a>
                    </li>
                    <li class="nav-item toggle-sidebar">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather sidebarCollapse feather-chevrons-left"><polyline points="11 17 6 12 11 7"></polyline><polyline points="18 17 13 12 18 7"></polyline></svg>
                    </li>
                </ul>

                <div class="shadow-bottom"></div>
                <div class="profile-info"><a href="/panel/my-profile">
                                    <div class="user-info">
                                        <div style="margin-right: 13px;" class="profile-img">
                                         <img src="<?= $customer->getAvatar(true) ?>" alt="avatar">
                                        </div>
                                        <div class="profile-content">
                                            <h6 class=""><?= $language::translate('Welcome') ?></h6>
                                            <p class=""><?= $customer->getFirstName() ?> <?= $customer->getLastName() ?></p>
                                        </div>
                                    </div>
                              </a>  </div>
                <ul class="list-unstyled menu-categories ps ps--active-y" id="accordionExample">

                    <li class="menu">
                       <a href="/panel" aria-expanded="false" class="dropdown-toggle">

                             <div class="icon-container">
                               <i class="ti-home fontkismi"></i>
                               <span>  <span><?= $language::translate('Home') ?></span></span>
                         </div>
                       </a>
                   </li>

                  <li class="menu menu-heading">
                    <div class="heading"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-circle"><circle cx="12" cy="12" r="10"></circle></svg><span><?= $language::translate('Buy Credit') ?></span></div>
                    </li>


                    <li class="menu">
                                     <a href="#starter-kit" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle">
                                       <div class="icon-container">
 <i class="ti-shopping-cart fontkismi"></i>
                                 <span><?= $language::translate('Buy Credit') ?></span>
                             </div>
                                        <div>
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevron-right"><polyline points="9 18 15 12 9 6"></polyline></svg>
                                        </div>
                                    </a>
                                    <ul class="collapse submenu list-unstyled" id="starter-kit" data-parent="#accordionExample">
                                        <li>
                                            <a href="/panel/buy-credit"><?= $language::translate('Buy Credit') ?></a>
                                        </li>
                                        <?php if($setting->getEvcStatus()) { ?>
                                          <li>
                                            <a href="/panel/buy-evc-credit"> EVC <?= $language::translate('Buy Credit') ?> </a>
                                        </li>
                                        <?php } ?>
                                    </ul>
                                              <li class="menu menu-heading">
                                                                    <div class="heading"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-circle"><circle cx="12" cy="12" r="10"></circle></svg><span><?= $language::translate('Credit Transactions') ?></span></div>
                                            </li>
						<li class="menu">
                        <a href="/panel/price-list" aria-expanded="false" class="dropdown-toggle">
                            <div class="icon-container">
<i class="ti-bookmark-alt fontkismi"></i>
                                <span><?= $language::translate('Price List') ?></span>
                            </div>
                        </a>
                    </li>


					<li class="menu">
                        <a href="/panel/credit-reports" aria-expanded="false" class="dropdown-toggle">
                            <div class="icon-container">
<i class="ti-archive fontkismi"></i>
                                <span><?= $language::translate('Credit Reports') ?></span>
                            </div>
                        </a>
                    </li>



                    <li class="menu menu-heading">
                                          <div class="heading"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-circle"><circle cx="12" cy="12" r="10"></circle></svg><span><?= $language::translate('File Transactions') ?></span></div>
                    </li>


					<li class="menu">
                        <a href="/panel/file-upload" aria-expanded="false" class="dropdown-toggle">
                            <div class="icon-container">
<i class="ti-cloud-up fontkismi"></i>
                                <span><?= $language::translate('File Upload') ?></span>
                            </div>
                        </a>
                    </li>
					<li class="menu">
                        <a href="/panel/my-files" aria-expanded="false" class="dropdown-toggle">
                            <div class="icon-container">
<i class="ti-folder fontkismi"></i>
                                <span><?= $language::translate('My Files') ?>
                                  <?php
                                  if ($ClientVehicleCounts) {
                                    ?>
                                   <span style="margin-left: 6px;padding: 2px 6px 2px 6px;    vertical-align: middle; text-transform: capitalize;" class="badge badge-danger"><?php echo count($ClientVehicleCounts) ?></span>
                                  <?php } ?>
                                </span>
                            </div>
                        </a>
                    </li>


                    <li class="menu menu-heading">
                                          <div class="heading"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-circle"><circle cx="12" cy="12" r="10"></circle></svg><span><?= $language::translate('Support Requests') ?></span></div>
                    </li>



					<li class="menu">
                        <a href="/panel/support" aria-expanded="false" class="dropdown-toggle">
                            <div class="icon-container">
<i class="ti-write fontkismi"></i>
                                <span><?= $language::translate('Support') ?>
                                  <?php
                                  if ($supportCounts) {
                                    ?>
                                   <span style="margin-left: 6px;padding: 2px 6px 2px 6px;    vertical-align: middle; text-transform: capitalize;" class="badge badge-danger"><?php echo count($supportCounts) ?></span>
                                  <?php } ?>
                                </span>
                            </div>
                        </a>
                    </li>




               <li class="menu menu-heading">
                                     <div class="heading"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-circle"><circle cx="12" cy="12" r="10"></circle></svg><span><?= $language::translate('Other') ?></span></div>
               </li>


					<li class="menu">
                        <a href="/panel/auto-tuner" aria-expanded="false" class="dropdown-toggle">
                            <div class="icon-container">
<i class="ti-layout-media-overlay fontkismi"></i>
                                <span><?= $language::translate('Vehicle Search') ?></span>
                            </div>
                        </a>
                    </li>
<li class="menu">
                        <a href="/panel/bosch-codes" aria-expanded="false" class="dropdown-toggle">
                            <div class="icon-container">
<i class="ti-plug fontkismi"></i>
                                <span><?= $language::translate('Bosch Codes') ?></span>
                            </div>
                        </a>
                    </li>


						<li class="menu">
                        <a href="/panel/p-codes" aria-expanded="false" class="dropdown-toggle">
                            <div class="icon-container">
<i class="ti-list fontkismi"></i>
                                <span><?= $language::translate('P Codes') ?></span>
                            </div>
                        </a>
                    </li>

                            <li class="menu menu-heading">
                                                  <div class="heading"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-circle"><circle cx="12" cy="12" r="10"></circle></svg><span><?= $language::translate('Policies') ?></span></div>
                            </li>

                            <li class="menu">
                                             <a href="#starter-kit" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle">
                                                <div class="">
                                                  <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-book-open"><path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"></path><path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"></path></svg>
                                                    <span><?= $language::translate('Policies') ?></span>
                                                </div>
                                                <div>
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevron-right"><polyline points="9 18 15 12 9 6"></polyline></svg>
                                                </div>
                                            </a>
                                            <ul class="collapse submenu list-unstyled" id="starter-kit" data-parent="#accordionExample">
                                                <li>
                                                    <a href="/panel/imprint"> <?= $language::translate('Imprint') ?> </a>
                                                </li>
                                                <li>
                                                    <a href="/panel/privacy-policy"> <?= $language::translate('Privacy Policy') ?> </a>
                                                </li>
                                                <li>
                                                    <a href="/panel/terms-and-conditions"> <?= $language::translate('Terms and conditions') ?> </a>
                                                </li>
                                                <li>
                                                    <a href="/panel/return-policy"> <?= $language::translate('Return Policy') ?> </a>
                                                </li>
                                                <li>
                                                    <a href="/panel/delivery-information"> <?= $language::translate('Delivery Information') ?> </a>
                                                </li>
                                            </ul>



                     <li class="menu menu-heading">
                                           <div class="heading"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-circle"><circle cx="12" cy="12" r="10"></circle></svg><span><?= $language::translate('Working Hours') ?></span></div>
                     </li>

                    <?php
                    foreach ($container->get('setting')->getWorkingHours(true) as $days => $hours) {?>
                        <li class="menu">
                            <a href="#" aria-expanded="false" class="dropdown-toggle">
                                <div class="icon-container">
                                    <i style="    color: #28a745;    font-weight: 800;     margin-right: -2px;
" class="ti-control-record fontkismi"></i>
                                    <span style="    font-size: 12px;" > <?= $language::translate(substr($days, 0, 3)) ?>: <?= $hours ?></span>
                                </div>
                            </a>
                        </li>
                    <?php }
                    ?>

                </ul>

            </nav>

        </div>
        <!--  END SIDEBAR  -->
