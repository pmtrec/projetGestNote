"use client"

import type React from "react"

import { useState } from "react"
import { useRouter } from "next/navigation"
import { Button } from "@/components/ui/button"
import { Input } from "@/components/ui/input"
import { Label } from "@/components/ui/label"
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from "@/components/ui/card"
import { Alert, AlertDescription } from "@/components/ui/alert"
import { GraduationCap, User, Lock } from "lucide-react"
import { useToast } from "@/hooks/use-toast"

// Comptes de test
const testAccounts = {
  admin: { email: "admin@sama-note.com", password: "admin123", role: "admin" },
  student: { email: "etudiant@sama-note.com", password: "etudiant123", role: "student" },
}

export default function LoginPage() {
  const [email, setEmail] = useState("")
  const [password, setPassword] = useState("")
  const [isLoading, setIsLoading] = useState(false)
  const [error, setError] = useState("")
  const router = useRouter()
  const { toast } = useToast()

  const handleLogin = async (e: React.FormEvent) => {
    e.preventDefault()
    setIsLoading(true)
    setError("")

    // Simulation d'une authentification
    await new Promise((resolve) => setTimeout(resolve, 1000))

    // Vérification des comptes de test
    const account = Object.values(testAccounts).find((acc) => acc.email === email && acc.password === password)

    if (account) {
      toast({
        title: "Connexion réussie",
        description: `Bienvenue ${account.role === "admin" ? "Administrateur" : "Étudiant"}!`,
      })

      // Redirection immédiate
      if (account.role === "admin") {
        router.push("/admin/dashboard")
      } else {
        router.push("/student/dashboard")
      }
    } else {
      setError("Email ou mot de passe incorrect")
      toast({
        title: "Erreur de connexion",
        description: "Vérifiez vos identifiants",
        variant: "destructive",
      })
    }

    setIsLoading(false)
  }

  return (
    <div className="min-h-screen bg-gradient-to-br from-beige-50 via-white to-tan-50 flex items-center justify-center p-4">
      <div className="w-full max-w-md space-y-8">
        {/* Header */}
        <div className="text-center space-y-4">
          <div className="flex justify-center">
            <div className="bg-brown-700 p-3 rounded-full shadow-lg">
              <GraduationCap className="h-8 w-8 text-white" />
            </div>
          </div>
          <div>
            <h1 className="text-3xl font-bold text-brown-800">Sama-Note</h1>
            <p className="text-brown-600 mt-2">Système de Gestion des Notes</p>
          </div>
        </div>

        {/* Formulaire de connexion */}
        <Card className="shadow-xl border-beige-200 bg-white">
          <CardHeader className="space-y-1">
            <CardTitle className="text-2xl text-center text-brown-800">Connexion</CardTitle>
            <CardDescription className="text-center text-brown-600">Connectez-vous à votre compte</CardDescription>
          </CardHeader>
          <CardContent>
            <form onSubmit={handleLogin} className="space-y-4">
              <div className="space-y-2">
                <Label htmlFor="email" className="text-brown-700">
                  Email
                </Label>
                <div className="relative">
                  <User className="absolute left-3 top-3 h-4 w-4 text-brown-400" />
                  <Input
                    id="email"
                    type="email"
                    placeholder="votre@email.com"
                    value={email}
                    onChange={(e) => setEmail(e.target.value)}
                    className="pl-10 border-beige-300 focus:border-brown-500 focus:ring-brown-500"
                    required
                  />
                </div>
              </div>

              <div className="space-y-2">
                <Label htmlFor="password" className="text-brown-700">
                  Mot de passe
                </Label>
                <div className="relative">
                  <Lock className="absolute left-3 top-3 h-4 w-4 text-brown-400" />
                  <Input
                    id="password"
                    type="password"
                    placeholder="••••••••"
                    value={password}
                    onChange={(e) => setPassword(e.target.value)}
                    className="pl-10 border-beige-300 focus:border-brown-500 focus:ring-brown-500"
                    required
                  />
                </div>
              </div>

              {error && (
                <Alert variant="destructive">
                  <AlertDescription>{error}</AlertDescription>
                </Alert>
              )}

              <Button
                type="submit"
                className="w-full bg-brown-700 hover:bg-brown-800 text-white shadow-md"
                disabled={isLoading}
              >
                {isLoading ? "Connexion..." : "Se connecter"}
              </Button>
            </form>
          </CardContent>
        </Card>

        {/* Comptes de test */}
        <Card className="bg-beige-100 border-beige-300">
          <CardHeader>
            <CardTitle className="text-lg text-brown-800">Comptes de test</CardTitle>
          </CardHeader>
          <CardContent className="space-y-3">
            <div className="text-sm">
              <p className="font-medium text-brown-700">👨‍💼 Administrateur :</p>
              <p className="text-brown-600">admin@sama-note.com / admin123</p>
            </div>
            <div className="text-sm">
              <p className="font-medium text-brown-700">🎓 Étudiant :</p>
              <p className="text-brown-600">etudiant@sama-note.com / etudiant123</p>
            </div>
          </CardContent>
        </Card>
      </div>
    </div>
  )
}
