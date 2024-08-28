@extends('layout.default')

@section('title')
    <title>Upload - {{ config('other.title') }}</title>
@endsection

@section('breadcrumbs')
    <li class="breadcrumbV2">
        <a href="{{ route('torrents.index') }}" class="breadcrumb__link">
            {{ __('torrent.torrents') }}
        </a>
    </li>
    <li class="breadcrumb--active">
        {{ __('common.upload') }}
    </li>
@endsection

@section('nav-tabs')
    <li class="nav-tabV2">
        <a class="nav-tab__link" href="{{ route('torrents.index') }}">
            {{ __('torrent.search') }}
        </a>
    </li>
    <li class="nav-tabV2">
        <a class="nav-tab__link" href="{{ route('top10.index') }}">
            {{ __('common.top-10') }}
        </a>
    </li>
    <li class="nav-tabV2">
        <a class="nav-tab__link" href="{{ route('rss.index') }}">
            {{ __('rss.rss') }}
        </a>
    </li>
    <li class="nav-tab--active">
        <a
            class="nav-tab--active__link"
            href="{{ route('torrents.create', ['category_id' => 1]) }}"
        >
            {{ __('common.upload') }}
        </a>
    </li>
@endsection

@section('main')
    <section
        class="upload panelV2"
        x-data="{
            cat: {{ (int) $category_id }},
            cats: JSON.parse(atob('{{ base64_encode(json_encode($categories)) }}')),
        }"
    >
        <h2 class="upload-title panel__heading">
            <i class="{{ config('other.font-awesome') }} fa-file"></i>
            {{ __('torrent.torrent') }}
        </h2>
        <div class="panel__body">
            <form
                name="upload"
                class="upload-form form"
                id="upload-form"
                method="POST"
                action="{{ route('torrents.store') }}"
                enctype="multipart/form-data"
            >
                @csrf
                <p class="form__group">
                    <label for="torrent" class="form__label">
                        Torrent {{ __('torrent.file') }}
                    </label>
                    <input
                        class="upload-form-file form__file"
                        type="file"
                        accept=".torrent"
                        name="torrent"
                        id="torrent"
                        required
                        @change="uploadExtension.hook(); cat = $refs.catId.value"
                    />
                </p>
                <p class="form__group">
                    <label for="nfo" class="form__label">
                        NFO {{ __('torrent.file') }} ({{ __('torrent.optional') }})
                    </label>
                    <input
                        id="nfo"
                        class="upload-form-file form__file"
                        type="file"
                        accept=".nfo"
                        name="nfo"
                    />
                </p>
                <p class="form__group" x-show="cats[cat].type === 'no' && cats[cat].id !== 3">
                    <label for="torrent-cover" class="form__label">
                        Cover {{ __('torrent.file') }} ({{ __('torrent.optional') }})
                    </label>
                    <input
                        id="torrent-cover"
                        class="upload-form-file form__file"
                        type="file"
                        accept=".jpg, .jpeg"
                        name="torrent-cover"
                    />
                </p>
                <p class="form__group" x-show="cats[cat].type === 'no' && cats[cat].id === 3">
                    <label for="torrent-cover" class="form__label">
                        Cover {{ __('torrent.file') }} (Obligatorio)
                    </label>
                    <input
                        id="torrent-cover"
                        class="upload-form-file form__file"
                        type="file"
                        required
                        accept=".jpg, .jpeg"
                        name="torrent-cover"
                    />
                </p>
                <p class="form__group" x-show="cats[cat].type === 'no'">
                    <label for="torrent-banner" class="form__label">
                        Banner {{ __('torrent.file') }} ({{ __('torrent.optional') }})
                    </label>
                    <input
                        id="torrent-banner"
                        class="upload-form-file form__file"
                        type="file"
                        accept=".jpg, .jpeg"
                        name="torrent-banner"
                    />
                </p>
                <p class="form__group">
                    <input
                        type="text"
                        name="name"
                        id="title"
                        class="form__text"
                        value="{{ $title ?: old('name') }}"
                        required
                    />
                    <label class="form__label form__label--floating" for="title">
                        {{ __('torrent.title') }}
                    </label>
                </p>
                <p class="form__group">
                    <select
                        x-ref="catId"
                        name="category_id"
                        id="autocat"
                        class="form__select"
                        required
                        x-model="cat"
                        @change="cats[cat].type = cats[$event.target.value].type;"
                    >
                        <option hidden selected disabled value=""></option>
                        @foreach ($categories as $id => $category)
                            <option class="form__option" value="{{ $id }}">
                                {{ $category['name'] }}
                            </option>
                        @endforeach
                    </select>
                    <label class="form__label form__label--floating" for="autocat">
                        {{ __('torrent.category') }}
                    </label>
                </p>
                <p class="form__group">
                    <select name="type_id" id="autotype" class="form__select" required>
                        <option hidden disabled selected value=""></option>
                        @foreach ($types as $type)
                            <option
                                value="{{ $type->id }}"
                                @selected(old('type_id') == $type->id)
                            >
                                {{ $type->name }}
                            </option>
                        @endforeach
                    </select>
                    <label class="form__label form__label--floating" for="autotype">
                        {{ __('torrent.type') }}
                    </label>
                </p>
                <p
                    class="form__group"
                    x-show="cats[cat].type === 'movie' || cats[cat].type === 'tv'"
                >
                    <select
                        name="resolution_id"
                        id="autores"
                        class="form__select"
                        x-bind:required="cats[cat].type === 'movie' || cats[cat].type === 'tv'"
                    >
                        <option hidden disabled selected value=""></option>
                        @foreach ($resolutions as $resolution)
                            <option
                                value="{{ $resolution->id }}"
                                @selected(old('resolution_id') == $resolution->id)
                            >
                                {{ $resolution->name }}
                            </option>
                        @endforeach
                    </select>
                    <label class="form__label form__label--floating" for="autores">
                        {{ __('torrent.resolution') }}
                    </label>
                </p>
                <div
                    class="form__group--horizontal"
                    x-show="cats[cat].type === 'movie' || cats[cat].type === 'tv'"
                >
                    <p class="form__group">
                        <select
                            name="distributor_id"
                            id="autodis"
                            class="form__select"
                            x-data="{ distributor: '' }"
                            x-model="distributor"
                            x-bind:class="distributor === '' ? 'form__select--default' : ''"
                        >
                            <option value="">{{ __('common.other') }}</option>
                            <option selected disabled hidden value=""></option>
                            @foreach ($distributors as $distributor)
                                <option
                                    value="{{ $distributor->id }}"
                                    @selected(old('distributor_id') == $distributor->id)
                                >
                                    {{ $distributor->name }}
                                </option>
                            @endforeach
                        </select>
                        <label class="form__label form__label--floating" for="autodis">
                            {{ __('torrent.distributor') }} (Only For Full Disc)
                        </label>
                    </p>
                    <p class="form__group">
                        <select
                            name="region_id"
                            id="autoreg"
                            class="form__select"
                            x-data="{ region: '' }"
                            x-model="region"
                            x-bind:class="region === '' ? 'form__select--default' : ''"
                        >
                            <option value="">{{ __('common.other') }}</option>
                            <option selected disabled hidden value=""></option>
                            @foreach ($regions as $region)
                                <option
                                    value="{{ $region->id }}"
                                    @selected(old('region_id') == $region->id)
                                >
                                    {{ $region->name }}
                                </option>
                            @endforeach
                        </select>
                        <label class="form__label form__label--floating" for="autoreg">
                            {{ __('torrent.region') }} (Only For Full Disc)
                        </label>
                    </p>
                </div>
                <div class="form__group--horizontal" x-show="cats[cat].type === 'tv'">
                    <p class="form__group">
                        <input
                            type="text"
                            name="season_number"
                            id="season_number"
                            class="form__text"
                            inputmode="numeric"
                            pattern="[0-9]*"
                            value="{{ old('season_number') }}"
                            x-bind:required="cats[cat].type === 'tv'"
                        />
                        <label class="form__label form__label--floating" for="season_number">
                            {{ __('torrent.season-number') }}
                        </label>
                        <span class="form__hint">
                            Numeric digits only. Use 0 only for specials and complete packs.
                        </span>
                    </p>
                    <p class="form__group">
                        <input
                            type="text"
                            name="episode_number"
                            id="episode_number"
                            class="form__text"
                            inputmode="numeric"
                            pattern="[0-9]*"
                            value="{{ old('episode_number') }}"
                            x-bind:required="cats[cat].type === 'tv'"
                        />
                        <label class="form__label form__label--floating" for="episode_number">
                            {{ __('torrent.episode-number') }}
                        </label>
                        <span class="form__hint">
                            Numeric digits only. Use 0 only for season packs and complete packs.
                        </span>
                    </p>
                </div>
                <div
                    class="form__group--horizontal"
                    x-show="cats[cat].type === 'movie' || cats[cat].type === 'tv'"
                >
                    <p class="form__group">
                        <input type="hidden" name="tmdb" value="0" />
                        <input
                            type="text"
                            name="tmdb"
                            id="autotmdb"
                            class="form__text"
                            inputmode="numeric"
                            pattern="[0-9]*"
                            x-bind:value="cats[cat].type === 'movie' || cats[cat].type === 'tv' ? '{{ $tmdb ?: old('tmdb') }}' : '0'"
                            x-bind:required="cats[cat].type === 'movie' || cats[cat].type === 'tv'"
                        />
                        <label class="form__label form__label--floating" for="autotmdb">
                            TMDB ID
                        </label>
                        <span class="form__hint">Numeric digits only.</span>
                        <output name="apimatch" id="apimatch" for="torrent"></output>
                    </p>
                    <p class="form__group">
                        <input type="hidden" name="imdb" value="0" />
                        <input
                            type="text"
                            name="imdb"
                            id="autoimdb"
                            class="form__text"
                            inputmode="numeric"
                            pattern="[0-9]*"
                            x-bind:value="cats[cat].type === 'movie' || cats[cat].type === 'tv' ? '{{ $imdb ?: old('imdb') }}' : '0'"
                            x-bind:required="cats[cat].type === 'movie' || cats[cat].type === 'tv'"
                        />
                        <label class="form__label form__label--floating" for="autoimdb">
                            IMDB ID
                        </label>
                        <span class="form__hint">Numeric digits only.</span>
                    </p>
                    <p class="form__group" x-show="cats[cat].type === 'tv'">
                        <input type="hidden" name="tvdb" value="0" />
                        <input
                            type="text"
                            name="tvdb"
                            id="autotvdb"
                            inputmode="numeric"
                            pattern="[0-9]*"
                            x-bind:value="cats[cat].type === 'tv' ? '{{ $tvdb ?: old('tvdb') }}' : '0'"
                            class="form__text"
                            x-bind:required="cats[cat].type === 'tv'"
                        />
                        <label class="form__label form__label--floating" for="autotvdb">
                            TVDB ID
                        </label>
                        <span class="form__hint">Numeric digits only.</span>
                    </p>
                    <p class="form__group">
                        <input type="hidden" name="mal" value="0" />
                        <input
                            type="text"
                            name="mal"
                            id="automal"
                            inputmode="numeric"
                            pattern="[0-9]*"
                            x-bind:value="cats[cat].type === 'movie' || cats[cat].type === 'tv' ? '{{ $mal ?: old('mal') }}' : '0'"
                            x-bind:required="cats[cat].type === 'movie' || cats[cat].type === 'tv'"
                            class="form__text"
                            placeholder=" "
                        />
                        <label class="form__label form__label--floating" for="automal">
                            MAL ID
                        </label>
                        <span class="form__hint">
                            Numeric digits only. Required for anime. Use 0 otherwise.
                        </span>
                    </p>
                </div>
                <p class="form__group" x-show="cats[cat].type === 'game'">
                    <input
                        type="text"
                        name="igdb"
                        id="autoigdb"
                        inputmode="numeric"
                        pattern="[0-9]*"
                        x-bind:value="cats[cat].type === 'game' ? '{{ $igdb ?: old('igdb') }}' : '0'"
                        class="form__text"
                        x-bind:required="cats[cat].type === 'game'"
                    />
                    <label class="form__label form__label--floating" for="autoigdb">
                        IGDB ID
                        <b>({{ __('torrent.required-games') }})</b>
                    </label>
                </p>
                <p class="form__group">
                    <input
                        type="text"
                        name="keywords"
                        id="autokeywords"
                        class="form__text"
                        value="{{ old('keywords') }}"
                        placeholder=" "
                    />
                    <label class="form__label form__label--floating" for="autokeywords">
                        {{ __('torrent.keywords') }} (
                        <i>{{ __('torrent.keywords-example') }}</i>
                        )
                    </label>
                </p>
                @livewire('bbcode-input', ['name' => 'description', 'label' => __('common.description'), 'required' => true])
                <p
                    class="form__group"
                    x-show="cats[cat].type === 'movie' || cats[cat].type === 'tv'"
                >
                    <textarea
                        id="upload-form-mediainfo"
                        name="mediainfo"
                        class="form__textarea"
                        placeholder=" "
                    >
{{ old('mediainfo') }}</textarea
                    >
                    <label class="form__label form__label--floating" for="upload-form-mediainfo">
                        {{ __('torrent.media-info-parser') }}
                    </label>
                </p>
                <p
                    class="form__group"
                    x-show="cats[cat].type === 'movie' || cats[cat].type === 'tv'"
                >
                    <textarea
                        id="upload-form-bdinfo"
                        name="bdinfo"
                        class="form__textarea"
                        placeholder=" "
                    >
{{ old('bdinfo') }}</textarea
                    >
                    <label class="form__label form__label--floating" for="upload-form-bdinfo">
                        BDInfo (Quick Summary)
                    </label>
                </p>
                <p class="form__group">
                    <input type="hidden" name="anon" value="0" />
                    <input
                        type="checkbox"
                        class="form__checkbox"
                        id="anon"
                        name="anon"
                        value="1"
                        @checked(old('anon'))
                    />
                    <label class="form__label" for="anon">{{ __('common.anonymous') }}?</label>
                </p>
                <p
                    class="form__group"
                    x-show="cats[cat].type === 'movie' || cats[cat].type === 'tv'"
                >
                    <input type="hidden" name="stream" value="0" />
                    <input
                        type="checkbox"
                        class="form__checkbox"
                        id="stream"
                        name="stream"
                        x-bind:value="cats[cat].type === 'movie' || cats[cat].type === 'tv' ? '1' : '0'"
                        @checked(old('stream'))
                    />
                    <label class="form__label" for="stream">
                        {{ __('torrent.stream-optimized') }}?
                    </label>
                </p>
                <p
                    class="form__group"
                    x-show="cats[cat].type === 'movie' || cats[cat].type === 'tv'"
                >
                    <input type="hidden" name="sd" value="0" />
                    <input
                        type="checkbox"
                        class="form__checkbox"
                        id="sd"
                        name="sd"
                        x-bind:value="cats[cat].type === 'movie' || cats[cat].type === 'tv' ? '1' : '0'"
                        @checked(old('sd'))
                    />
                    <label class="form__label" for="sd">{{ __('torrent.sd-content') }}?</label>
                </p>
                @if (auth()->user()->group->is_modo || auth()->user()->group->is_internal)
                    <p class="form__group">
                        <input type="hidden" name="internal" value="0" />
                        <input
                            type="checkbox"
                            class="form__checkbox"
                            id="internal"
                            name="internal"
                            value="1"
                            @checked(old('internal'))
                        />
                        <label class="form__label" for="internal">
                            {{ __('torrent.internal') }}?
                        </label>
                    </p>
                @endif

                <p class="form__group">
                    <input type="hidden" name="personal_release" value="0" />
                    <input
                        type="checkbox"
                        class="form__checkbox"
                        id="personal_release"
                        name="personal_release"
                        value="1"
                        @checked(old('personal_release'))
                    />
                    <label class="form__label" for="personal_release">Personal Release?</label>
                </p>
                @if ($user->group->is_trusted)
                    <p class="form__group">
                        <input type="hidden" name="mod_queue_opt_in" value="0" />
                        <input
                            type="checkbox"
                            class="form__checkbox"
                            id="mod_queue_opt_in"
                            name="mod_queue_opt_in"
                            value="1"
                            @checked(old('mod_queue_opt_in'))
                        />
                        <label class="form__label" for="mod_queue_opt_in">
                            Opt in to Moderation Queue?
                        </label>
                    </p>
                @endif

                @if (auth()->user()->group->is_modo || auth()->user()->group->is_internal)
                    <p class="form__group">
                        <input type="hidden" name="refundable" value="0" />
                        <input
                            type="checkbox"
                            class="form__checkbox"
                            id="refundable"
                            name="refundable"
                            value="1"
                            @checked(old('refundable'))
                        />
                        <label class="form__label" for="refundable">
                            {{ __('torrent.refundable') }}?
                        </label>
                    </p>
                @endif

                @if (auth()->user()->group->is_modo || auth()->user()->group->is_internal)
                    <p class="form__group">
                        <select name="free" id="free" class="form__select">
                            <option
                                value="0"
                                @selected(old('free') === '0' || old('free') === null)
                            >
                                {{ __('common.no') }}
                            </option>
                            <option value="25" @selected(old('free') === '25')>25%</option>
                            <option value="50" @selected(old('free') === '50')>50%</option>
                            <option value="75" @selected(old('free') === '75')>75%</option>
                            <option value="100" @selected(old('free') === '100')>100%</option>
                        </select>
                        <label class="form__label form__label--floating" for="free">
                            {{ __('torrent.freeleech') }}
                        </label>
                    </p>
                @endif

                <p class="form__group">
                    <button
                        type="submit"
                        class="form__button form__button--filled"
                        name="post"
                        value="true"
                        id="post"
                    >
                        {{ __('common.submit') }}
                    </button>
                </p>
            </form>
        </div>
    </section>
@endsection

@if ($user->can_upload ?? $user->group->can_upload)
    @section('sidebar')
        <section class="panelV2">
            <h2 class="panel__heading">
                <i class="{{ config('other.font-awesome') }} fa-info"></i>
                {{ __('common.info') }}
            </h2>
            <div class="panel__body">
                <p>
                    {{ __('torrent.announce-url') }}:
                    <a
                        x-data="upload"
                        data-announce-url="{{ route('announce', ['passkey' => $user->passkey]) }}"
                        x-on:click.prevent="copy"
                        href="{{ route('announce', ['passkey' => $user->passkey]) }}"
                    >
                        {{ route('announce', ['passkey' => $user->passkey]) }}
                    </a>
                </p>
                <p>
                    {{ __('torrent.announce-url-desc', ['source' => config('torrent.source')]) }}
                </p>
                <p class="text-success">
                    {!! __('torrent.announce-url-desc-url', ['url' => config('other.upload-guide_url')]) !!}
                </p>
            </div>
        </section>
    @endsection
@endif

@section('javascripts')
    <script src="{{ asset('build/unit3d/tmdb.js') }}" crossorigin="anonymous"></script>
    <script src="{{ asset('build/unit3d/parser.js') }}" crossorigin="anonymous"></script>
    <script src="{{ asset('build/unit3d/helper.js') }}" crossorigin="anonymous"></script>
    <script src="{{ asset('build/unit3d/imgbb.js') }}" crossorigin="anonymous"></script>
    <script nonce="{{ HDVinnie\SecureHeaders\SecureHeaders::nonce('script') }}">
        document.addEventListener('alpine:init', () => {
            Alpine.data('upload', () => ({
                copy() {
                    navigator.clipboard.writeText(this.$el.dataset.announceUrl);
                    Swal.fire({
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 3000,
                        icon: 'success',
                        title: 'Copied to clipboard!',
                    });
                },
            }));
        });
    </script>
@endsection
