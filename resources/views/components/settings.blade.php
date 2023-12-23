
<div class="bg-gray-200 dark:bg-gray-800 bg-opacity-25 grid grid-cols-1 md:grid-cols-2 gap-6 lg:gap-8 p-6 lg:p-8">
    <form action="{{ route('settings.submit') }}" method="post">
        @csrf

        <x-input :hidden="true" name="id" :value="$settings->id" />

        <x-label for="city_id" value="{{ __('City ID') }}" />
        <x-input name="city_id" :value="$settings->city_id" />
        <x-input-error for="city_id"></x-input-error>


        <x-label for="category_id" value="{{ __('Category ID') }}" />
        <x-input name="category_id" :value="$settings->category_id" />
        <x-input-error for="category_id"></x-input-error>


        <x-label for="salary" value="{{ __('With Salary') }}" />
        <x-checkbox :checked="$settings->salary ? true : false"  name="salary" :value="$settings->salary"  />
        <x-input-error for="salary"></x-input-error>

        <br>
        <x-button>{{ __("Submit") }}</x-button>

    </form>
</div>
