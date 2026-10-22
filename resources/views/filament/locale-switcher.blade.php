<form method="POST" action="{{ route('locale.update') }}" style="display:flex;align-items:center;gap:.35rem;margin-inline:.75rem">
    @csrf
    <button type="submit" name="locale" value="vi" aria-label="Tiếng Việt" aria-pressed="{{ app()->getLocale() === 'vi' ? 'true' : 'false' }}" style="padding:.35rem .5rem;border:0;border-radius:.25rem;background:{{ app()->getLocale() === 'vi' ? '#b91c1c' : 'transparent' }};color:{{ app()->getLocale() === 'vi' ? '#fff' : 'inherit' }};font-size:.75rem;font-weight:600;cursor:pointer">VI</button>
    <button type="submit" name="locale" value="en" aria-label="English" aria-pressed="{{ app()->getLocale() === 'en' ? 'true' : 'false' }}" style="padding:.35rem .5rem;border:0;border-radius:.25rem;background:{{ app()->getLocale() === 'en' ? '#b91c1c' : 'transparent' }};color:{{ app()->getLocale() === 'en' ? '#fff' : 'inherit' }};font-size:.75rem;font-weight:600;cursor:pointer">EN</button>
</form>
