# Powered by https://makefiles.dev/

export PHP_ERROR_EXCEPTION_DEPRECATIONS=true

################################################################################

-include .makefiles/Makefile
-include .makefiles/pkg/php/v1/Makefile

.makefiles/%:
	@curl -sfL https://makefiles.dev/v1 | bash /dev/stdin "$@"
