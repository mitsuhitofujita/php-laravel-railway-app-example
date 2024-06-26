<h2>鉄道会社追加</h2>
<div>
    {{ html()->modelForm($initialValues, 'POST', '/admin/railway_providers')->open() }}
        <div>
            {{ html()->label('適用開始日')->for('railway-providers-create-valid-from') }}
            {{ html()->text('valid_from')->id('railway-providers-create-valid-from') }}
            @error('valid_from')
                {{ html()->span($message) }}
            @enderror
        </div>
        <div>
            {{ html()->label('鉄道会社名')->for('railway-providers-create-name') }}
            {{ html()->text('name')->id('railway-providers-create-name') }}
            @error('name')
                {{ html()->span($message) }}
            @enderror
        </div>
        <div>
            {{ html()->hidden('token')->id('railway-providers-create-token') }}
            @error('token')
                {{ html()->span($message) }}
            @enderror
        </div>
        <div>
            {{ html()->submit('新規追加') }}
        </div>
    {{ html()->form()->close() }}
</div>
