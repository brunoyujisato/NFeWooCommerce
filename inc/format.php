<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class WooCommerceNFe_Format extends WooCommerceNFe {

    function cpf( $string ){

		if (!$string) return;
		$string = self::clear( $string );
		$string = self::mask($string,'###.###.###-##');
		return $string;

	}

	function cnpj( $string ){

		if (!$string) return;
		$string = self::clear( $string );
		$string = self::mask($string,'##.###.###/####-##');
		return $string;

	}

	function cep( $string ){

		if (!$string) return;
		$string = self::clear( $string );
		$string = self::mask($string,'#####-###');
		return $string;

	}

	function clear( $string ) {

        $string = str_replace( array(',', '-', '!', '.', '/', '?', '(', ')', ' ', '$', 'R$', '€'), '', $string );
        return $string;

	}

	function mask($val, $mask) {
	   $maskared = '';
	   $k = 0;
	   for($i = 0; $i<=strlen($mask)-1; $i++){
           if($mask[$i] == '#'){
               if(isset($val[$k]))
                   $maskared .= $val[$k++];
           }
           else {
               if(isset($mask[$i])) $maskared .= $mask[$i];
           }
	   }
	   return $maskared;
	}

    function is_cpf( $cpf ) {
		$cpf = preg_replace( '/[^0-9]/', '', $cpf );

		if ( 11 != strlen( $cpf ) || preg_match( '/^([0-9])\1+$/', $cpf ) ) {
			return false;
		}

		$digit = substr( $cpf, 0, 9 );

		for ( $j = 10; $j <= 11; $j++ ) {
			$sum = 0;

			for( $i = 0; $i< $j-1; $i++ ) {
				$sum += ( $j - $i ) * ( (int) $digit[ $i ] );
			}

			$summod11 = $sum % 11;
			$digit[ $j - 1 ] = $summod11 < 2 ? 0 : 11 - $summod11;
		}

		return $digit[9] == ( (int) $cpf[9] ) && $digit[10] == ( (int) $cpf[10] );
	}

    function is_cnpj( $cnpj ) {
		$cnpj = sprintf( '%014s', preg_replace( '{\D}', '', $cnpj ) );

		if ( 14 != ( strlen( $cnpj ) ) || ( 0 == intval( substr( $cnpj, -4 ) ) ) ) {
			return false;
		}

		for ( $t = 11; $t < 13; ) {
			for ( $d = 0, $p = 2, $c = $t; $c >= 0; $c--, ( $p < 9 ) ? $p++ : $p = 2 ) {
				$d += $cnpj[ $c ] * $p;
			}

			if ( $cnpj[ ++$t ] != ( $d = ( ( 10 * $d ) % 11 ) % 10 ) ) {
				return false;
			}
		}

		return true;
	}

}
