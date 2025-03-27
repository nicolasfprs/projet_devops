<?php
use PHPUnit\Framework\TestCase;

class RegisterTest extends TestCase
{
    /**
     * Test basique de validation du mot de passe
     */
    public function testPasswordValidation()
    {
        // Fonction simple pour valider le mot de passe
        $validatePassword = function($password) {
            return strlen($password) >= 8 && 
                   preg_match('/[A-Za-z]/', $password) && 
                   preg_match('/[0-9]/', $password);
        };
        
        // Test avec un mot de passe valide
        $this->assertTrue(
            $validatePassword('Password123'),
            "Un mot de passe valide devrait être accepté"
        );
        
        // Test avec un mot de passe trop court
        $this->assertFalse(
            $validatePassword('Pass1'),
            "Un mot de passe trop court devrait être rejeté"
        );
        
        // Test avec un mot de passe sans chiffre
        $this->assertFalse(
            $validatePassword('PasswordOnly'),
            "Un mot de passe sans chiffre devrait être rejeté"
        );
    }
}