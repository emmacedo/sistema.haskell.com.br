{{-- Máscara dinâmica de telefone: fixo (00) 0000-0000 ou celular (00) 00000-0000, conforme a quantidade de dígitos (mesmo comportamento do cadastro público). --}}
<script>
    $(function() {
        function formatPhone(raw) {
            var digits = raw.replace(/\D/g, '').substring(0, 11);
            if (digits.length > 10) {
                return digits.replace(/^(\d{2})(\d{5})(\d{0,4}).*/, '($1) $2-$3');
            }
            if (digits.length > 6) {
                return digits.replace(/^(\d{2})(\d{4})(\d{0,4}).*/, '($1) $2-$3');
            }
            if (digits.length > 2) {
                return digits.replace(/^(\d{2})(\d*)/, '($1) $2');
            }
            return digits;
        }

        $(document).on('input', 'input[name="phone"], input[name="phone2"]', function() {
            $(this).val(formatPhone($(this).val()));
        });
    });
</script>
