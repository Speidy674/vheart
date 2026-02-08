<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Actions\ImportClipAction;
use App\Enums\Clips\CompilationClipStatus;
use App\Enums\Clips\CompilationStatus;
use App\Enums\Clips\CompilationType;
use App\Models\Clip;
use App\Models\Clip\Compilation;
use App\Models\Scopes\ClipPermissionScope;
use App\Models\User;
use App\Services\Twitch\Data\ClipDto;
use App\Services\Twitch\TwitchEndpoints;
use App\Services\Twitch\TwitchService;
use Exception;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Log;

class InitialEpisodeSeeder extends Seeder
{
    // Only raw clip ids pls
    protected const array Clips = [
        // Episode 1
        'BigImpartialWalletAMPEnergy-8RYRjexnyvbeNfrV',
        'EnticingLongNuggetsCurseLit-1LUi7d9JzNhMZ2Rx',
        'EndearingCrazyPepperoniEagleEye-DKddR1AmIcTylJAe',
        'AntediluvianUnsightlyKimchiKappaWealth-uRvVzUsNDG8MYR0K',
        'DeafIcyAmazonJonCarnage-HNKpsG0CfnVHbo83',
        'ElatedCuriousPepperoniFloof-emscHxhWG5Pav1gB',
        'OddBlindingPuffinAliens-oQ97t1DQIK7DtnfX',
        'ArbitraryMuddyTomatoVoteYea-URKp4wkFAxFXh6jq',
        'DeterminedWittyEggplantKappaClaus-i6lhKvoXjXD5wVMh',
        'RefinedJazzyStarlingJonCarnage-VJVqWbUGIAeu_heU',
        'JollyUnusualKuduBudBlast-rCtPoY1KJJsVd-2_',
        'SlipperyCallousAsteriskHoneyBadger-Ih993Pbrme0Yxp1d',
        'BitterAlertGoatKappaClaus-Ijl-kmTL-oO1Gm4u',
        'LazyAliveCroissantAMPTropPunch-UosBLCxh8lpIyHwg',
        'BlatantCleverLemurKappaRoss-YjqBo02ib6rIUgFj',
        'DeterminedGleamingMooseHotPokket-jp6PZREvcUDYM4aK',
        'CooperativeBeautifulCourgetteNomNom-Xmm8Lg18BzVewWdR',
        'DependablePreciousDootKappaClaus-BSAcRgIl9k_HYzAI',
        'CuriousObliviousKathyWutFace-XYHby8Zp2Zvcrc-G',
        'AgileFurryPlumberBigBrother-NectoRiE70STavvR',
        'FaithfulSpoopyTortoiseTakeNRG-tvo9yVbXy9PoshUq',
        'PrettyAmazingMochaRalpherZ-3jvM5cgcwxiec6hs',
        'ClumsyGloriousNightingaleTooSpicy-0U-EAoDIXzzKB9rS',
        'CleverFaithfulWatercressTF2John-cu8rRTahL8ATnnMA',
        'TenuousSparklingWeaselBCWarrior-Pu9ggzbJZxDEd8wd',
        'SpookyProductivePhoneSwiftRage-3J7WJVEn-vypPHdx',
        'CreativeTalentedAniseTBTacoRight--zmezpz1T3vNI1Z8',
        'AgitatedWrongMartenAMPEnergyCherry-QtACzJvoGLjV7baP',
        'AmericanRudeTeaTinyFace-hTVT-gthooViMrYF',
        'HardDreamyMilkRuleFive-1HqkB9C9ccszgUMC',
        'SpotlessAcceptableLionDancingBanana-2IvjUPPoasjB3Psa',
        'HorribleAdventurousRavenPeteZaroll-sbI3t7oKgy_beI_h',
        // Episode 2
        'ExuberantEnticingWitchHeyGuys-Wcv6qzZUhQ-mJX9n',
        'TallCrazyChickenTBTacoRight-4AY0kmUinFFw6Lsc',
        'KawaiiEnchantingClintDeIlluminati-CtVitmcX5PxkPojz',
        'TransparentBoredHamsterFunRun-oUCKYudFhRsGC6cB',
        'SparklyPoorMangetoutPeanutButterJellyTime-AP1RmGLR2h1yqnuA',
        'BoldRudeCheesecakeJonCarnage-eqQTB1K5_aHc2hHn',
        'SeductiveCleverPigAMPTropPunch-Z2V4PkrK_mGXmsN0',
        'ArborealPeacefulZebraSpicyBoy-E_mCesApy4766iRo',
        'PiliableBlazingTomatoAMPTropPunch-gizvB689hU74zqOh',
        'ManlyPrettyElephantDuDudu--Su7d54XIENigrcI',
        'SparklingCovertSwallowSeemsGood-WKQomIHiUqmS76xu',
        'GentleFineSandwichHassanChop-M1Wluwk5gKSbVf1G',
        // Episode 3
        'MuddyTenderChowderGrammarKing-DhpWCkO1YYbD2ktG',
        'BlindingSpineyArugulaNerfBlueBlaster-XhmWQJEyG_VnWXca',
        'CuriousSneakyPterodactylEagleEye-_tNUZyR1PGZWsyTF',
        'ModernCooperativeDumplingsRalpherZ-aRB5GaMZBA2fIoti',
        'GeniusFurtiveSparrowRedCoat-lWR5nEZEdwUlDiVx',
        'SuaveExuberantDonutItsBoshyTime-JztkICSXZb589SrN',
        'ImportantSpotlessStarlingFreakinStinkin-IbKyBsNxcjRI0DCC',
        'FrailInnocentEyeballHumbleLife-qAXpnQimMA7Fg6Mq',
        'RenownedWittyVanillaPJSalt-6WCURajzS_AuUjvw',
        'CrowdedObeseCurlewRitzMitz-refDpDPdnGSFlGLa',
        'LittleGeniusGarbagePMSTwin-08tpWz10gWvTis-y',
        'CredulousYummyHamPartyTime-iJzaT0DvAh3aUmPp',
        'AmusedAliveRamenTBCheesePull-YbpQkgs36gSvnIfQ',
        'EntertainingDeafCrabsYouDontSay-xtPD-UPufRlb0Wus',
        'AntsyThoughtfulDiscSSSsss-kXiWTMseykD53Gc_',
        'SuccessfulConsideratePorcupineWholeWheat-cjDlwC3cEH-xWLpx',
        'PrettyInterestingCoyoteKappaPride-neXiGp39m6znQvPq',
        'ShinyUglySpiderPartyTime-jtK5Rw_GOE87AqYD',
        // Episode 4
        'EmpathicCoweringCroissantEagleEye-wQ8LO6CYfaj4gExY',
        'ThirstyTalentedCiderHassanChop-O9ANf8uGedEfC4gn',
        'CrepuscularHotAlpacaTwitchRaid-lE9PVmmC1va0KQMB',
        'SlipperyAbstruseChamoisVoHiYo-pV9BNLSlWc15VLoK',
        'LazyEasyYamNinjaGrumpy-UD-vjxILeuSYEUnb',
        'TrustworthyAgreeableClipsdadDerp-SnIk_VNcajkw8qqD',
        'ExpensiveHeartlessEelPogChamp-hH_6bG2XFQaXpTr_',
        'GlamorousPlainGarlicSpicyBoy-dCCgf0KdQCCA5vfp',
        'SwissTangiblePanOhMyDog-of8PtewY5xs0k14k',
        'KawaiiOutstandingMinkRedCoat-sS42R8gh8JlfmTqt',
        'DifferentPeacefulGrasshopperGrammarKing-dFvpEBvzZRX22aYi',
        'KitschySolidHorseAMPTropPunch-bBp1xVsm1XgmXd7e',
        'EphemeralWonderfulPterodactylPipeHype-kQDnPcXnU9ZYXnbm',
        'LightObeseChimpanzeeJebaited-dVimA5iCjWPROneg',
        'OptimisticSaltyMageUnSane-hbz6i-p6SOLPUZm_',
        'DarlingTentativeButterflyChefFrank-e0pvDCb45SrQVkcr',
        'RamshackleVainSowWTRuck-P1KLDht79Zp2VzD4',
        'PoorSmallWombatBigBrother-PZv10lEgbzYP4Ivo',
        'PlausibleAgileMetalDerp-uwRJX5qZ3ov9qWrQ',
        'CovertSaltyTigerYee-UcOWZ1Oy7G7htwbz',
        'UgliestHonorableParrotSuperVinlin-jOGrifsdMbWzNn3A',
        'CuteSuccessfulCrabsEleGiggle-XunRXm9dgCXoJzO_',
        'MagnificentDelightfulCheesecakeHeyGirl-8dv3cmEizdwS8zS2',
        'FragileColdCrowGingerPower-xSZ12TTp30OswROp',
        'DirtyDifficultBearBCWarrior-6nhMG9EOeFqm9y75',
        'PluckyBoxyMelonVoHiYo-Jx-MqEelxXy6HXaO',
        'SuspiciousIronicRhinocerosJonCarnage-xBwNgbHoISEW7wTS',
        'PeacefulTenuousPhonePJSugar-VVYwLi24I_zmHwOU',
        'LachrymoseFilthyPonySaltBae-A4WwhGJc89g3UAA-',
        'GoldenTangentialPassionfruitChefFrank-XNTCvR4idRnxVTMb',
        'EnchantingTallArtichokeKAPOW-5qaxCLnKEoDvGMye',
        'BumblingAffluentPelicanWholeWheat-I9ThAX3bJ98QpDob',
        'EnergeticAmericanBillSSSsss-bS6G979TVzzhVzyE',
        'CooperativeMoldyWrenchSuperVinlin-6sNe7pVEsMj28XRw',
        'AmericanFragileStarYee-rRhitio2lORaJRTx',
        'SpookyArtsyMarjoramThisIsSparta-EFUe_l-sq1ZkQgeA',
        'UglyAwkwardPheasantSmoocherZ-A0qQYHlawSjYqtba',
    ];

    protected const array Episodes = [
        [
            'user_id' => 0,
            'title' => 'Episode 1',
            'slug' => 'episode-1',
            'youtube_url' => 'https://www.youtube.com/watch?v=D9PHIxhU_MM',
            'status' => CompilationStatus::Published,
            'clips' => [
                'GenerousBoxySwanFloof-HM_jprvcQDR7WnwR',
                'PlausibleSavorySwordCurseLit-y9LZMS1ERrSSq4N4',
                'HelplessEagerTardigradeSaltBae-LKKuYwxmKhbOgD1G',
                'InexpensiveResilientAppleOSkomodo-sbNBVHuO8fDaMmtn',
                'LachrymoseTardyPuddingDoritosChip-FrBrXz-wnWcIXgbD',
                'FrozenSeductiveSoybeanTBTacoLeft-68OrgaC-HEgyXI2_',
                'SoftTemperedDeerSmoocherZ-Pg5m0Sz5AdCAUJWO',
                'CourteousVastDragonfruitDatSheffy-aMnD1QIF9SoSiQwQ', // Source URL does not exist anymore, twitch still gives us a clip tho
                'BovineWittyPepperoniSuperVinlin-S1ETPMeUJrPoRoYV',
                'CalmKathishParrotTF2John-4ZpmI1AfPQoEsd_R',
                'CrepuscularHelpfulWaspVoHiYo-x1qSTRXwuhnCsoV4',
                'GentleNimblePanFreakinStinkin-1eErSWrf0oTe4DNb',
                'CooperativeAmericanPheasantMingLee-5FXZZ91UFu9b-8T3',
                'HeadstrongOnerousParrotDogFace-Pzk4pAjq0Ws-AuY5',
                'IgnorantMuddyLocustAMPEnergy-VUBWoHLFCEteN3C_',
                'AbnegateSlipperyBulgogiUnSane-uWS1v7mESK2e_6MQ',
                'SuccessfulResourcefulPlumPartyTime-iPGxDnmhkiYcc1p7',
                'CharmingSquareLyrebirdCoolStoryBob-Di5d7-2Ju-O_0VWW',
                'AverageAgilePancakeKevinTurtle-MI4eLE8P2oPOsoKs',
                'CleverKnottyUdonPRChase-N_zw9BP7soQFegFG',
                'ShinyColdbloodedMageCeilingCat-M9KaMexmbCUdXvW4',
                'FamousAltruisticCucumberSaltBae-jMssVVE4G8F1DOWz',
                'EasyIronicSharkSaltBae-YoQgrQCxYcGoKwFd',
                'TiredPreciousMartenKappaWealth-zFzxz3qlrQYGOWS3',
                'ExcitedAwkwardMarjoramCmonBruh-e6fNA_ZXMkyW12k', // ?
                'GlamorousImpartialClipzTTours-ZU8SbZn5ZEGHbRV4',
                'SpotlessFaintClipsmomPartyTime-iVeYirUOukt2MMqK',
                'InspiringAnnoyingPigOMGScoots-MkuSyJ9cYFx1kCtA',
                'ExpensiveCrunchyElephantKappaClaus-aN4xFHHxAPyUOnM9',
                'CreativeApatheticAlpacaM4xHeh-vtAnNPvrgXYZPJTS',
                'DeadKindDadUnSane-bRoTdW1iy5ZwuWsN',
                'TangibleTalentedPonyRalpherZ-TENdUqv0CL1FhKPq',
                'PunchyWanderingDootImGlitch-pRyUHeGFUXT9vr3U',
                'SavoryTangibleNigiriVoHiYo-3y-dv8tbBQgLWYvZ',
                'HealthyConcernedSwallowUnSane-EeJIsA8ePcjGi62v',
                'TalentedShyGarageMVGame-keqU8I7XJ5FI1G34',
                'AmericanSecretiveChipmunkCoolCat-y5MA7WfJuezTJ6le',
                'ClearDeadWatermelonHassaanChop-bWWVSUUvMXNAbTd3',
                'LuckyPrettyWrenchTBTacoLeft-GzKmEqva9LLHVHVs',
                'AttractiveEvilWrenImGlitch-duvV8mJIFIq4C1QT',
                'GrotesqueSpicyFoxHassanChop-Np4vO_C33mjI7G5b',
                'CulturedAverageWerewolfCharlieBitMe-5SdXIxXzpiI9Qt2y',
                'StupidHumbleBeeResidentSleeper-nP9QXjKx26XY9k_D',
                'BelovedPowerfulHumanVoteYea-JA2ieqE2dTp7Bfdw',
                'DifferentSpikyLlamaTheTarFu-UK8RRfYhb_A2XFpQ',
                'FaintBoringButterSuperVinlin-PcB6n7v1YFpgvpSe',
                'ScrumptiousDaintyCocoaBrokeBack-nZJf9zZ-OAIc6LS6',
                'CleverKnottyUdonPRChase-N_zw9BP7soQFegFG',
                'TalentedSpicyAlbatrossNinjaGrumpy-DLzVtz7H4r9R1qMJ',
                'BoxyFineBorkMrDestructoid-Nz0rwTv3eXGtUM0n',
                'VastMushyElkKappaWealth-UvFk5M20T1-uNIee',
                'WanderingImportantBasenjiBigBrother-ba-JFKyDNodYdOWH',
                'PatientDifficultSpiderDerp-3XSJ5Sy9MSFPvcac',
                'SmellyGorgeousIguanaThunBeast-QCwJQNN9fAO9o9RM',
            ],
        ],
        [
            'user_id' => 0,
            'title' => 'Episode 2',
            'slug' => 'episode-2',
            'youtube_url' => 'https://www.youtube.com/watch?v=2tQbOkXfdGc',
            'status' => CompilationStatus::Published,
            'clips' => [
                'PerfectCallousApeNotLikeThis-FmdLIDFMAJ4Rjze5',
                'SplendidPiercingPuddingArsonNoSexy-OMj5X6TA1lxCedfq',
                'LazyIntelligentFennelNotATK-sBs1Tln-4tZKGK4J',
                'SaltyBovinePrariedogAliens-b10f7yJI_ijIJJh-',
                'DarkDeadLyrebirdYee-yTjGwJRT9qkc3YR5',
                'ImportantIntelligentParrotHassanChop-xglTuLCPxtVjHBDM',
                'TangibleBetterHorseradishThisIsSparta-Pwo4iY6ud5njguhv',
                'SwissCourteousBaconTBTacoRight-Aaz5mLHUOoPTQSPX',
                'BusyEnticingPonyTheTarFu-S1t8cOA_gE2Vtb9W',
                'FairEmpathicLaptopOhMyDog-LkjlqxgRzwflZ4wm',
                'HilariousAntsyDonkeyNerfRedBlaster-FEINwuAhhUrBur3l',
                'ChillyRealFishDBstyle-PFpjB4JV3VH5-GSG',
                'ClearDelightfulEggCclamChamp-9pe64XifCPUUwAOf',
                'CulturedMiniatureBubbleteaVoteNay-x5JgfuLHQwtYx5Qh',
                'VibrantEphemeralQuailMrDestructoid-o_1ixWnAdkB7jDWH',
                'FriendlyHeadstrongBillGOWSkull-hYvbWd2hdoVtoeMR',
                'YawningEnticingDragonfruitItsBoshyTime-XRA3St7AI1k49hML',
                'HyperBusyPenguinUnSane-R6nZS_PRE57qp_py',
                'SpineyCrunchyFriesOMGScoots-sS7QVVGAHK5Sxa4Q',
                'MoldyTolerantPeppermintCoolStoryBro-u4ZMYFl6vahmQD1V',
                'GrotesqueStupidSnoodWholeWheat-Qc4jPte4HUOp-2dX',
                'ShortWittyMangoWOOP-QZeZj9AVcvUdYOfW',
                'NeighborlySparklingRaisinPeoplesChamp-wTnyIx7hMs_ZI8YY',
                'FrailImpossibleJellyfishDancingBaby-zO5_xSX-bOdq_Ftt',
                'SplendidImpossibleKuduLitty-Oa0BwA37orawprrN',
                'DignifiedHappyFennelGingerPower-G1R4wu6DxjZ4sKBm',
                'FurryMoistSkirretTooSpicy-eTWeXugYLK385YVc',
                'TastyAntsyCookieEagleEye-QoE9GHi-zgDTD8jh',
                'ShakingStrangeAdminWutFace-6VNLFSas71V71QXF',
                'SarcasticBlightedSangPRChase-iwD6ZpLo6KNnt279',
                'LovelyEndearingPenguinArgieB8-cLQ2v0eRxnsAWp9Y',
                'SillyCreativePlumageStoneLightning-6HYMKhnGQx7Qp9ht',
                'OriginalSarcasticLaptopHeyGirl-SaGymc43jgUACVnV',
                'DepressedPlausibleTomatoKreygasm-1NEsfCaFaWk4HCuc',
                'MotionlessCloudyLegTinyFace-B8sJIwc8G49VNnkR',
                'BenevolentImportantOysterVoteYea-bMXRDYEDki7USUIZ',
                'SweetAgileTarsierOSsloth-RM6QEHEJ1TGUuXHE',
                'AmusedCautiousAxeMikeHogu-X0Dk8hcCAMFKaiR_',
                'DeliciousAbrasiveOstrichOhMyDog-DoRvCFstZ3PY9Nl2',
                'SparklyDeterminedVanillaUWot-WS3svdoabNb12QGm',
                'TastyMoldyPheasantMingLee-oxjvdI7OpTMj_BxN',
                'ConfidentTrustworthyToothBabyRage-amBLhsBrzjHpgL2-',
                'MuddySlickChimpanzeeJebaited-CeEAhbA410rFqmDL',
                'TamePopularJalapenoPrimeMe-onoMzK-aqVqlGAdt',
                'UnusualViscousJalapenoDxCat-Yb4Bs4UEKa_kYhWQ',
                'HelpfulFlaccidWolfLeeroyJenkins-F7Bp_fEc8Dvv0c8k',
                'KathishOilyBottleTooSpicy-1Thwa6Is8VH82eqh',
            ],
        ],
        [
            'user_id' => 0,
            'title' => 'Episode 3',
            'slug' => 'episode-3',
            'youtube_url' => 'https://www.youtube.com/watch?v=-BX7qzTCt3U',
            'status' => CompilationStatus::Published,
            'clips' => [
                'PhilanthropicAstuteRamenMau5-iHpoNwR2ay55Rt6f',
                'AnimatedGoldenLaptopRitzMitz-DKPrbIj7lJzFyyK7',
                'OptimisticMiniatureChickpeaHassanChop-qS0zkmYczWSYAAwU',
                'CoyIncredulousGooseStrawBeary-G4b9M0wpGKE-ekfc',
                'StylishImpartialOrcaHeyGuys-lmObfR7X38JtQMW4',
                'BreakableAnnoyingBobaTheTarFu-gm8il_EIarJvyUvc',
                'BoredFitEyeballPraiseIt-cDvHV09Ba5tSB6Hb',
                'GoodCallousMonkeyAMPEnergyCherry-wFg-XUTexcqt8SIQ',
                'BraveBumblingMonkeyJKanStyle-ujWXzs-Jz1bc73kQ',
                'DrabInventiveBibimbapTakeNRG-HlGljvlNFuySqlIY',
                'OptimisticPolishedZebraDansGame-JbwNl0XK-70cnKs6',
                'BlushingWimpyCasetteDatBoi-urxkRPureBgccey_',
                'EmpathicInterestingTermiteNotLikeThis-t14_6HDIkIfFRyrn',
                'MushySpineyJackalOSfrog-f6ORyCUEJ0tclaYa',
                'FrailClumsyDadAMPEnergy-GxadwZ61U6xsxmN9',
                'EndearingNastyOxKippa-6ZQBCAFPhNzCFQl7',
                'TenuousCrowdedCarrotEagleEye-t-gy-2ht4QyEN9S3',
                'LachrymoseLightDiamondPlanking-IOz7U2ySvMW4c3ue',
                'BitterKitschyFiddleheadsPeoplesChamp-aiT5GANb5ebo3czb',
                'FunOptimisticTrollPRChase-36HigJphxyYhwww8',
                'MuddyConcernedGerbilPJSugar-q4VK_xoSKRGaVRUs',
                'ObedientGiftedPhoneHassaanChop-JojRk22BBuvSKC9T',
                'JoyousKindPangolinAMPEnergy-SqtiEytiqGMHRArD',
                'BrightZealousCodAliens-0DU5tTVQKVjQB9xV',
                'CoweringPlausibleSalamanderAMPEnergyCherry-Noqh0qaHBANyHazb',
                'ThirstyCallousPieBlargNaut-c1Pa2-CKdlsGmMtT',
                'KnottyArborealYakinikuFeelsBadMan-MaoUkRfna03-a_v1',
                'OutstandingCrispyChimpanzeeGivePLZ-a-XNZqlb-gQ_aXLG',
                'ObliviousScaryKathyBibleThump-9P0tCp2svNC70VxG',
                'SingleTriangularPidgeonBrainSlug-zA4Kp-BckWCNqv24',
                'OptimisticTangibleDogeUWot-TjkhPnsbdskziBI1',
                'SwissKnottyLousePoooound-bAh3WUe1pXUWTync',
                'SuccessfulArborealBaboonKAPOW-o7-YESrsTJvlBcU-',
                'PiercingEsteemedLardMrDestructoid-O8NlVDOif2rUhCDo',
                'WonderfulOddVultureYee-mUHZx-F8CJl7j32b',
                'FastSwissSpindleSoonerLater-nz8QSt7IrTVjmR4j',
                'IntelligentColdTildeVoHiYo-4BSv_xg__-nwCQAU',
                'WittySassyCroissantPoooound-dnh0LrXD_Ub4Q9dQ',
                'SullenBrightCobraGivePLZ-5saN78vW9W0MvqlS',
                'SquareCreativeClamNerfBlueBlaster-B5sf3Kvk63eWJwuv',
                'ArtsyTenaciousMoonBrainSlug-XmjMhrMW_XzI0Kyb',
                'HappySuaveWatercressFUNgineer-aZqh0TxfbZjTrsDj',
                'SpikyTangibleDotterelAliens-Ywzv0-w6uCeg2UH5',
                'BlushingRudeLeopardDatSheffy-zVzLV96z4iJLcGbs',
                'SnappyShakingDeerHassaanChop-1CbBdTGMVID2kcK8',
            ],
        ],
        [
            'user_id' => 0,
            'title' => 'Episode 4',
            'slug' => 'episode-4',
            'youtube_url' => 'https://www.youtube.com/watch?v=AC9bdrzWiOo',
            'status' => CompilationStatus::Published,
            'clips' => [
                'AnimatedRoughTapirJonCarnage-3LsA-0wcV8jH42mi',
                'PoliteThankfulCrabAsianGlow-f8MWSZp93alqkU3i',
                'DeterminedRichChickpeaCoolCat-jttXk3pwqwTUDwV7',
                'LaconicEndearingKimchiDansGame--ys2b-0FNysKu7Mn',
                'AdventurousLivelyBearCeilingCat-k7AcO3476q5gSlGn',
                'EncouragingKindHornetMcaT-Xid-73s0LspQQA3L',
                'EncouragingHelpfulFishBibleThump-oqs0OvKSSrAGrbmB',
                'ResilientHeadstrongGrouseJebaited-sX4VApYf7ozG-Hap',
                'KathishHomelyCrowPlanking-SCM-bL7CgQkc24ID',
                'TawdryHardFriseeBudBlast-VlaBIgZThOHrcmSi',
                'SweetHomelyChoughJKanStyle-27iiaAUv5PIYmNXe',
                'CautiousMoldyDragonfruitPanicBasket-XcBV8v-OgnxxAdI4',
                'SmellyFrailDelicataGivePLZ-QDmKaDwuKuJtZJf3',
                'PunchyBitterChipmunkDoritosChip-O8oX32lAmvKfG3Xk',
                'ClearSlickOxArgieB8-mRQVHijMFYaicXND',
                'TrustworthyPlayfulMagpieKippa-zfvCFt-4rfwdZPdv',
                'CrowdedBlitheTrollPJSugar-XNe8cZe872riPg-p',
                'MoralIntelligentNostrilItsBoshyTime-8R-3k_p4qGJKH_Y1',
                'AgreeableLazyChickpeaPermaSmug-LRVNuz-DccaV54b3',
                'TameSmoothWasabiChefFrank-8Hf60aMnPxSU5QX1',
                'SincereThankfulKoupreyMoreCowbell-rqWUMhL9asACRPji',
                'BetterStupidYogurtPhilosoraptor-SoFVyYpR5EOEi466',
                'InquisitiveArbitraryChickpeaOSkomodo-F9g_YYkrPzcjBxQN',
                'TawdryAnnoyingPrariedogJKanStyle-tOedvV4J16uvjmDM',
                'AnimatedNiceLardPrimeMe-jLAxTci8xutSXnuv',
                'SuaveWanderingSlothPipeHype-l980bWzBr58WAfyD',
                'SinglePlumpCatKAPOW-naHcPLEC2kxrnNxb',
                'EsteemedDreamyAlmondKeepo-LYNWtJx1AgphG-42',
                'SnappyDeafOtterNerfBlueBlaster-sVQlgo7dBc-aDb3d',
                'ZealousSmallMomKeyboardCat-AYdvTTavJ8TEd3pr',
                'TallFantasticSnailLitFam-A-lhkcyXhTRpKv-M',
                'BraveShakingAlpacaSSSsss-Jy1XiZAxrOGLd_l9',
                'BlushingFrailSheepPunchTrees-F1LdWe_JXV8Cj9PE',
                'FlirtyHedonisticLaptopLeeroyJenkins-XsWdnPktoBbvrLVW',
                'CrowdedSpunkyDotterelDerp-0hf71PHgrZlvg0w-',
                'ShortCorrectCheeseKappaRoss-nj7SCUvFm4SqgPFr',
                'CooperativePricklyGooseSaltBae-v9qjwG25wYFn0n3q',
                'LivelyDiligentGorillaJKanStyle-9sW9dY5oPvOYuMNO',
                'HumbleWonderfulFerretM4xHeh-gfSzPjyNlXRKM-0G',
            ],
        ],
    ];

    /**
     * Run the database seeds.
     */
    public function run(ImportClipAction $importClipAction, TwitchService $twitchService): void
    {
        if (Compilation::count() > 0 || app()->environment('testing')) {
            return;
        }

        $systemUser = User::find(0);
        $allClips = collect(self::Clips);

        foreach (self::Episodes as $episode) {
            $allClips = $allClips->merge($episode['clips']);
        }

        $allClips = $allClips->values()->unique();
        $twitchClips = collect();
        $missingClips = collect();

        Log::notice("Importing {$allClips->count()} Clips...");

        $allClips->chunk(100)->each(function ($chunk, $index) use ($twitchService, &$twitchClips, &$missingClips) {
            $requestedIds = $chunk->values()->toArray();
            $params = ['id' => $requestedIds];

            try {
                /** @var ClipDto[] $clips */
                $clips = $twitchService->get(TwitchEndpoints::GetClips, $params);

                $fetchedClips = collect($clips);

                $missingCount = count($requestedIds) - $fetchedClips->count();
                if ($missingCount > 0) {
                    $foundIds = $fetchedClips->pluck('id')->toArray();
                    $missingIds = array_diff($requestedIds, $foundIds);
                    $missingClips = $missingClips->merge($missingIds)->values()->unique();

                    Log::warning("Chunk {$index}: Requested ".count($requestedIds).' clips, but Twitch only returned '.$fetchedClips->count().'. Missing: '.implode(', ', $missingIds));
                } else {
                    Log::info("Chunk {$index}: Successfully fetched all ".count($requestedIds).' clips.');
                }

                $twitchClips = $twitchClips->merge($fetchedClips)->values();
            } catch (Exception $e) {
                Log::error('Twitch API Error: '.$e->getMessage());

                return;
            }

            sleep(1);
        });

        $twitchClips->each(function (ClipDto $clip) use ($systemUser, $importClipAction) {
            $importClipAction->execute($clip, $systemUser, true);
        });

        Log::notice("{$twitchClips->count()} Clips have been imported.");

        foreach (self::Episodes as $episodeData) {
            /** @var Compilation $compilation */
            $compilation = Compilation::create([
                'user_id' => 0,
                'title' => $episodeData['title'],
                'slug' => $episodeData['slug'],
                'status' => $episodeData['status'],
                'type' => CompilationType::LongVideo,
                'youtube_url' => $episodeData['youtube_url'],
                'created_at' => $episodeData['created_at'] ?? now(),
                'updated_at' => now(),
            ]);

            $clips = Clip::query()
                ->withoutGlobalScope(ClipPermissionScope::class)
                ->whereIn('twitch_id', $episodeData['clips'])
                ->pluck('id')
                ->map(function (int $id) {
                    return [
                        'clip_id' => $id,
                        'claimed_by' => 0,
                        'claimed_at' => now(),
                        'status' => CompilationClipStatus::Completed,
                    ];
                });

            $compilation->clips()->sync($clips);
        }
    }
}
