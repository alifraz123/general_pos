<aside class="main-sidebar">
  <!-- sidebar: style can be found in sidebar.less -->
  <section class="sidebar">

    <!-- sidebar menu: : style can be found in sidebar.less -->
    <ul class="sidebar-menu" data-widget="tree">

      <li class="{{Request::is('admin') ? 'active' : ''}}">
        <a href="/">
          <i class="fa fa-dashboard"></i> <span>Dashboard</span>
        </a>
      </li>




      <li class=" treeview {{Request::is('roles') ? 'active' : ''}} {{Request::is('userType') ? 'active' : ''}}
      {{Request::is('createUser') ? 'active' : ''}} 

      {{Request::is('company') ? 'active' : ''}} {{Request::is('useraccount') ? 'active' : ''}}
          {{Request::is('accountcompany') ? 'active' : ''}} {{Request::is('accountemployee') ? 'active' : ''}}

           {{Request::is('zone') ? 'active' : ''}} {{Request::is('city') ? 'active' : ''}}
          {{Request::is('area') ? 'active' : ''}} 

          {{Request::is('itemsCategory') ? 'active' : ''}}
          {{Request::is('items') ? 'active' : ''}} 
          {{Request::is('getPaymentType') ? 'active' : ''}}
          {{Request::is('getCustomerType') ? 'active' : ''}}
          {{Request::is('Customer') ? 'active' : ''}}
          {{Request::is('Purchaser') ? 'active' : ''}}
          {{Request::is('getChartOfAccounts') ? 'active' : ''}}
      ">
        <a href="#">
          <i class="fa fa-bars"></i> <span>Basic Menu</span>
          <span class="pull-right-container">
            <i class="fa fa-angle-left pull-right"></i>
          </span>
        </a>
        <ul class="treeview-menu">

          <li class="treeview {{Request::is('roles') ? 'active' : ''}} {{Request::is('userType') ? 'active' : ''}}
          {{Request::is('createUser') ? 'active' : ''}} 
          ">
            <a href="">
              <i class="fa fa-user"></i> <span>User Book</span>
              <span class="pull-right-container">
                <i class="fa fa-angle-left pull-right"></i>
              </span>
            </a>
            <ul class="treeview-menu">

              <li class="{{Request::is('createUser') ? 'active' : ''}}"><a href="/createUser"><i
                    class="fa fa-user-plus"></i>
                  User Profile</a></li>
              @if (Auth::user()->UserType == 'Admin')
              <li class="{{Request::is('ManageUser') ? 'active' : ''}}"><a href="/ManageUser"><i
                    class="fa fa-user-plus"></i>
                  Manage users</a>
              </li>

              @endif

            </ul>

          </li>


          @if(Auth::user()->CustomerIndustry == 'Regular')
          <li class="treeview {{Request::is('Customer') ? 'active' : ''}} ">
            <a href="">
              <i class="fa fa-user"></i> <span>Customers</span>
              <span class="pull-right-container">
                <i class="fa fa-angle-left pull-right"></i>
              </span>
            </a>
            <ul class="treeview-menu">
              <li class="{{Request::is('Customer') ? 'active' : ''}}"><a href="/Customer"><i
                    class="fa fa-user-plus"></i>
                  Customer</a>
              </li>
            </ul>
          </li>
          @endif

         
        <li class="treeview {{Request::is('Purchaser') ? 'active' : ''}} ">
          <a href="">
            <i class="fa fa-user"></i> <span>Purchasers</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <li class="{{Request::is('Purchaser') ? 'active' : ''}}">
              <a href="/Purchaser"><i class="fa fa-user-plus"></i> Purchaser</a>
            </li>
          </ul>
        </li>
          



          <li class="treeview {{Request::is('itemsCategory') ? 'active' : ''}}
          {{Request::is('items') ? 'active' : ''}}
          {{Request::is('getChartOfAccounts') ? 'active' : ''}}
          {{Request::is('getPaymentType') ? 'active' : ''}}"
          >

            <a href="">
              <i class="fa fa-cubes"></i> <span>Items Book</span>
              <span class="pull-right-container">
                <i class="fa fa-angle-left pull-right"></i>
              </span>
            </a>
            <ul class="treeview-menu">

              <li class="{{Request::is('items') ? 'active' : ''}}"><a href="/items"><i class="fa fa-circle-o"></i>
                  Items</a></li>
              <li class="{{Request::is('itemsCategory') ? 'active' : ''}}"><a href="/itemsCategory"><i
                    class="fa fa-circle-o">

                  </i> Items Category</a>
              </li>

              {{-- <li id="Payment_Type_Menu" class="{{Request::is('getPaymentType') ? 'active' : ''}}"><a
                  href="/getPaymentType">
                  <i class="fa fa-circle-o">

                  </i>Payment Type</a>
              </li> --}}

              <li id="Chart_Of_Accounts_Menu" class="{{Request::is('getChartOfAccounts') ? 'active' : ''}}"><a
                href="/getChartOfAccounts">
                <i class="fa fa-circle-o">

                </i>Chart Of Accounts</a>
            </li>


            </ul>

          </li>


        </ul>
      </li>




      <li class="treeview {{Request::is('salebook') ? 'active' : ''}} {{Request::is('edit_salesbook') ? 'active' : ''}}
      {{Request::is('SaleReturn') ? 'active' : ''}} {{Request::is('edit_SaleReturn') ? 'active' : ''}}
      ">
        <a href="#">
          <i class="fa fa-paper-plane"></i>
          <span>Salebook</span>
          <span class="pull-right-container">
            <i class="fa fa-angle-left pull-right"></i>
          </span>
        </a>
        <ul class="treeview-menu">


          <li class="{{Request::is('salebook') ? 'active' : ''}}"><a href="/salebook"><i
                class="fa fa-balance-scale"></i>Sale Invoice</a></li>

          <li class="{{Request::is('edit_salesbook') ? 'active' : ''}}"><a href="/edit_salesbook"><i
                class="fa fa-pencil"></i>Edit Sale Invoice</a></li>

          <li class="{{Request::is('SaleReturn') ? 'active' : ''}}"><a href="/SaleReturn"><i
                class="fa fa-balance-scale"></i>Sale Return</a></li>

          <li class="{{Request::is('edit_SaleReturn') ? 'active' : ''}}"><a href="/edit_SaleReturn"><i
                class="fa fa-pencil"></i>Edit Sale Return</a></li>


        </ul>
      </li>
      {{-- <li class="treeview {{Request::is('quotation') ? 'active' : ''}} {{Request::is('edit_quotation') ? 'active' : ''}}
      {{Request::is('SaleReturn') ? 'active' : ''}} {{Request::is('edit_SaleReturn') ? 'active' : ''}}
      ">
        <a href="#">
          <i class="fa fa-paper-plane"></i>
          <span>Quotation</span>
          <span class="pull-right-container">
            <i class="fa fa-angle-left pull-right"></i>
          </span>
        </a>
        <ul class="treeview-menu">


          <li class="{{Request::is('quotation') ? 'active' : ''}}"><a href="/quotation"><i
                class="fa fa-balance-scale"></i>Quotation</a></li>

          <li class="{{Request::is('edit_quotation') ? 'active' : ''}}"><a href="/edit_quotation"><i
                class="fa fa-pencil"></i>Edit Quotation</a></li>

        </ul>
      </li> --}}




      <li
        class="treeview {{Request::is('purchasebook') ? 'active' : ''}} {{Request::is('edit_purchasebook') ? 'active' : ''}}">
        <a href="#">
          <i class="fa fa-paper-plane"></i>
          <span>Purchase Book</span>
          <span class="pull-right-container">
            <i class="fa fa-angle-left pull-right"></i>
          </span>
        </a>
        <ul class="treeview-menu">


          <li class="{{Request::is('purchasebook') ? 'active' : ''}}"><a href="/purchasebook"><i
                class="fa fa-balance-scale"></i>Purchase Book</a></li>

          <li class="{{Request::is('edit_purchasebook') ? 'active' : ''}}"><a href="/edit_purchasebook"><i
                class="fa fa-pencil"></i>Edit Purchase Invoice</a></li>


        </ul>
      </li>

      <li
        class="treeview {{Request::is('expensebook') ? 'active' : ''}} {{Request::is('edit_expensebook') ? 'active' : ''}}">
        <a href="#">
          <i class="fa fa-paper-plane"></i>
          <span>Expense Book</span>
          <span class="pull-right-container">
            <i class="fa fa-angle-left pull-right"></i>
          </span>
        </a>
        <ul class="treeview-menu">


          <li class="{{Request::is('expensebook') ? 'active' : ''}}"><a href="/expensebook"><i
                class="fa fa-balance-scale"></i>Expense Book</a></li>

          <li class="{{Request::is('edit_expense') ? 'active' : ''}}"><a href="/edit_expensebook"><i
                class="fa fa-pencil"></i>Edit Expense Invoice</a></li>


        </ul>
      </li>

      <li class="treeview {{Request::is('Reports') ? 'active' : ''}}">
        <a href="#">
          <i class="fa fa-flag"></i>
          <span>Reports</span>
          <span class="pull-right-container">
            <i class="fa fa-angle-left pull-right"></i>
          </span>
        </a>
        <ul class="treeview-menu">

          <li class="{{Request::is('Reports') ? 'active' : ''}}">
            <a href="/Reports">
              <i class="fa fa-flag"></i>Reports
            </a>
          </li>

        </ul>
      </li>

    </ul>
  </section>
  <!-- /.sidebar -->
</aside>

<script>

</script>