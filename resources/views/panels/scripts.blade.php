    {{-- Vendor Scripts --}}
        <script src="{{ asset(mix('vendors/js/vendors.min.js')) }}"></script>
        <script src="{{ asset(mix('vendors/js/ui/prism.min.js')) }}"></script>
        @yield('vendor-script')
        {{-- Theme Scripts --}}
        <script src="{{ asset(mix('js/core/app-menu.js')) }}"></script>
        <script src="{{ asset(mix('js/core/app.js')) }}"></script>
        <script src="{{ asset(mix('js/scripts/components.js')) }}"></script>
@if($configData['blankPage'] == false)
        <script src="{{ asset(mix('js/scripts/footer.js')) }}"></script>
@endif
        {{-- page script --}}
        @yield('page-script')
        <script src="{{ asset(mix('vendors/js/extensions/sweetalert2.all.min.js')) }}"></script>
        <script>
          $(function(){
            $('.deep-link-help').tooltip({title: 'Deep Link',placement: 'top'});
            $('.spoof-referrer-help').tooltip({title: 'Deep Link',placement: 'top'});
            getDomain = (dest_url) => {
              let Url = dest_url.match(/(http(s)?:\/\/.)?(www\.)?[-a-zA-Z0-9@:%._\+~#=]{2,256}\.[a-z]{2,6}\b([-a-zA-Z0-9@:%_\+.~#?&//=]*)/g);
              let flag = 0;
              if (Url) {
                const Item = new URL(dest_url);
                if (Item['hostname'].indexOf('amazon.') == -1) flag = 1;
              }
              else flag = 1;
              if (flag) return false;
              return true;
            }
          })
        </script>

