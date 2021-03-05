# Provides auto-complete for artisan commands
# source: https://gist.github.com/jhoff/8fbe4116d74931751ecc9e8203dfb7c4

_artisan()
{
    COMP_WORDBREAKS=${COMP_WORDBREAKS//:}
    COMMANDS=`php artisan --raw --no-ansi list | sed "s/[[:space:]].*//g"`
    COMPREPLY=(`compgen -W "$COMMANDS" -- "${COMP_WORDS[COMP_CWORD]}"`)
    return 0
}
complete -F _artisan art
complete -F _artisan artisan
