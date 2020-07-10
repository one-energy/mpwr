<x-app.auth :title="__('Best Practices')">
  <div>
    <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">
      <div class="px-4 py-5 sm:px-6">
        <div class="md:flex justify-between">
          <div class="md:flex justify-start">
            <h3 class="text-lg text-gray-900">Training</h3>
          </div>
          <div class="flex md:justify-end sm:justify-start">
            <div class="pt-2 relative md:mx-auto sm:mx-0 text-gray-600">
              <input class="border-2 border-gray-300 bg-white h-10 px-5 pr-16 rounded-lg text-sm focus:outline-none"
                type="search" name="search" placeholder="Search for Training">
              <button type="submit" class="absolute right-0 top-0 mt-5 mr-4">
                <svg class="text-gray-600 h-4 w-4 fill-current" xmlns="http://www.w3.org/2000/svg"
                  xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" id="Capa_1" x="0px" y="0px"
                  viewBox="0 0 56.966 56.966" style="enable-background:new 0 0 56.966 56.966;" xml:space="preserve"
                  width="512px" height="512px">
                  <path
                    d="M55.146,51.887L41.588,37.786c3.486-4.144,5.396-9.358,5.396-14.786c0-12.682-10.318-23-23-23s-23,10.318-23,23  s10.318,23,23,23c4.761,0,9.298-1.436,13.177-4.162l13.661,14.208c0.571,0.593,1.339,0.92,2.162,0.92  c0.779,0,1.518-0.297,2.079-0.837C56.255,54.982,56.293,53.08,55.146,51.887z M23.984,6c9.374,0,17,7.626,17,17s-7.626,17-17,17  s-17-7.626-17-17S14.61,6,23.984,6z" />
                </svg>
              </button>
            </div>
          </div>
        </div>
        <div class="text-gray-600"><a href="/training" class="underline">Setting</a> / <a href="/training/setting" class="underline">Best Practices</a></div>

        <div class="mt-3">
          <div class="grid md:grid-cols-2 sm:grid-cols-1 md:row-gap-4 sm:row-gap-0 col-gap-4">
            <div class="col-span-1">
              <div class="grid grid-cols-10 row-gap-4 col-gap-4 border-gray-200 md:border-2 border-t-2 p-4 md:rounded-lg">
                <div class="col-span-9">
                  When on the doors
                </div>
                <div class="col-span-1">
                  <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
                    <symbol id="arrow" viewBox="0 0 25 25">
                      <path d="M5 3l3.057-3 11.943 12-11.943 12-3.057-3 9-9z" class="text-gray-500 fill-current" />
                    </symbol>
                    <use xlink:href="#arrow" width="15" height="15" y="6" x="6" />
                  </svg>
                </div>
              </div>
            </div>

            <div class="col-span-1">
              <a href="{{route('what-to-say.index')}}">
                <div class="grid grid-cols-10 row-gap-4 col-gap-4 border-gray-200 md:border-2 border-t-2 p-4 md:rounded-lg">
                  <div class="col-span-9">
                    What to say
                  </div>
                  <div class="col-span-1">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
                      <symbol id="arrow" viewBox="0 0 25 25">
                        <path d="M5 3l3.057-3 11.943 12-11.943 12-3.057-3 9-9z" class="text-gray-500 fill-current" />
                      </symbol>
                      <use xlink:href="#arrow" width="15" height="15" y="6" x="6" />
                    </svg>
                  </div>
                </div>
              </a>
            </div>

            <div class="col-span-1">
              <div class="grid grid-cols-10 row-gap-4 col-gap-4 border-gray-200 md:border-2 border-t-2 p-4 md:rounded-lg">
                <div class="col-span-9">
                  How to say it
                </div>
                <div class="col-span-1">
                  <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
                    <symbol id="arrow" viewBox="0 0 25 25">
                      <path d="M5 3l3.057-3 11.943 12-11.943 12-3.057-3 9-9z" class="text-gray-500 fill-current" />
                    </symbol>
                    <use xlink:href="#arrow" width="15" height="15" y="6" x="6" />
                  </svg>
                </div>
              </div>
            </div>

            <div class="col-span-1">
              <div class="grid grid-cols-10 row-gap-4 col-gap-4 border-gray-200 md:border-2 border-t-2 p-4 md:rounded-lg">
                <div class="col-span-9">
                  What time of the day
                </div>
                <div class="col-span-1">
                  <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
                    <symbol id="arrow" viewBox="0 0 25 25">
                      <path d="M5 3l3.057-3 11.943 12-11.943 12-3.057-3 9-9z" class="text-gray-500 fill-current" />
                    </symbol>
                    <use xlink:href="#arrow" width="15" height="15" y="6" x="6" />
                  </svg>
                </div>
              </div>
            </div>

            <div class="col-span-1">
              <div class="grid grid-cols-10 row-gap-4 col-gap-4 border-gray-200 md:border-2 border-t-2 border-b-2 p-4 md:rounded-lg">
                <div class="col-span-9">
                  Misc. Training
                </div>
                <div class="col-span-1">
                  <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
                    <symbol id="arrow" viewBox="0 0 25 25">
                      <path d="M5 3l3.057-3 11.943 12-11.943 12-3.057-3 9-9z" class="text-gray-500 fill-current" />
                    </symbol>
                    <use xlink:href="#arrow" width="15" height="15" y="6" x="6" />
                  </svg>
                </div>
              </div>
            </div>
          </div>
        </div>

      </div>
    </div>
  </div>
</x-app.auth>